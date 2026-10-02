<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComponenteModel;
use App\Models\AreaModel;
use App\Models\TipoComponenteModel;
use App\Models\DepositoModel;
use App\Models\HistoriaModel;
use App\Models\EstadoComponenteModel;
use App\Models\ImpresoraModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ImpresoraController extends Controller
{
    public function index()
    {
        $componentesModel = new ComponenteModel();

        // Para el wizard necesitamos TODOS los tóners disponibles
        // (con stock, sin stock y en uso) igual que hace PcController con getComponenteByTipoForPc
        $toners = $componentesModel->getComponenteByTipoForPc('Toner', '');
        $impresoras = ImpresoraModel::with(['area', 'deposito', 'componentes.tipo'])->get();

        $historias = HistoriaModel::where('tipo_id', 7)
            ->orderBy('created_at', 'desc')
            ->get();

        $tipos     = TipoComponenteModel::all();
        $depositos = DepositoModel::all();
        $areas     = AreaModel::orderBy('nombre', 'asc')->get();

        return view('gest_impresoras', [
            'historias'  => $historias,
            'depositos'  => $depositos,
            'areas'      => $areas,
            'toners'     => $toners,
            'impresoras' => $impresoras,
        ]);
    }

    public function store(Request $request)
    {
        $user      = Auth::user();
        $areaModel = new AreaModel();

        $request->validate([
            'addNombre'        => 'required|string|max:255|unique:impresora,nombre',
            'addIdentificador' => 'required|string|max:255|unique:impresora,identificador',
            'addEnUso'         => 'required|boolean',
            'addArea'          => 'nullable|required_if:addEnUso,1|exists:area,id',
            'addDeposito'      => 'nullable|required_if:addEnUso,0|exists:deposito,id',
        ]);

        DB::transaction(function () use ($request, $user, $areaModel) {

            $impresora = new ImpresoraModel();
            $impresora->nombre = $request->input('addNombre');
            $impresora->identificador = $request->input('addIdentificador');
            $impresora->ip = $request->input('addIp');
            $impresora->marca_modelo = $request->input('addMarca');
            $impresora->deposito_id = $request->input('addDeposito') ?: null;

            if (! $request->input('addDeposito')) {
                $nombreArea = AreaModel::find($request->input('addArea'))->nombre;
                if ($request->input('addNroConsul')) {
                    $nombreArea .= ' ' . $request->input('addNroConsul');
                }

                $area = $areaModel->findByName($nombreArea);
                if ($area) {
                    $impresora->area_id = $area->id;
                } else {
                    $areaNueva = new AreaModel();
                    $areaNueva->nombre = $nombreArea;
                    $areaNueva->visible = false;
                    $areaNueva->save();
                    $impresora->area_id = $areaNueva->id;
                }
            }

            $componenteController = new ComponenteController();
            $modo = $request->input('addToner_modo', 'stock');

            // ── Modo sin-stock: el tóner existe pero sin stock → se le agrega stock
            //    y se descuenta 1 unidad que queda "en uso" en la impresora.
            //
            //    Caso normal: la fila seleccionada tiene estado_id = 7 (Sin stock).
            //    Caso especial: la fila seleccionada tiene estado_id = 5 (En uso),
            //    lo que significa que el modelo generó una fila virtual porque el
            //    componente solo existe en uso y no tiene fila Sin stock real.
            //    En ese caso se busca o crea una fila Sin stock en deposito_origen_id
            //    y sobre ella se opera, nunca sobre la fila En uso.
            if ($modo === 'sin-stock') {
                $tonerId  = $request->input('addToner');
                $cantidad = (int) $request->input('addToner_cantidad', 1);
                $motivo   = trim($request->input('addToner_motivo', ''));

                if ($cantidad < 1) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'addToner_cantidad' => 'La cantidad a ingresar debe ser un entero mayor o igual a 1.',
                    ]);
                }
                if ($motivo === '') {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'addToner_motivo' => 'El motivo del ingreso de stock es obligatorio.',
                    ]);
                }

                $componente = ComponenteModel::with('deposito', 'depositoOrigen')
                    ->whereKey(abs($tonerId))  
                    ->lockForUpdate()
                    ->first();

                if (! $componente) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'addToner' => 'El tóner seleccionado no existe.',
                    ]);
                }

                // ── Caso especial: la fila que llegó es En uso (virtual del modelo).
                //    Buscar una fila Sin stock / Disponible real en deposito_origen_id.
                //    Si no existe, crearla como Sin stock con stock = 0.
                //    El ingreso de stock siempre opera sobre una fila con estado 4 o 7,
                //    nunca sobre la fila En uso.
                if ((int) $componente->estado_id === ComponenteController::ESTADO_EN_USO) {
                    $depositoReal = $componente->deposito_origen_id;

                    $filaReal = ComponenteModel::where('nombre', $componente->nombre)
                        ->where('tipo_id', $componente->tipo_id)
                        ->whereIn('estado_id', [
                            ComponenteController::ESTADO_DISPONIBLE,
                            ComponenteController::ESTADO_SIN_STOCK,
                        ])
                        ->where(function ($q) use ($depositoReal) {
                            $depositoReal
                                ? $q->where('deposito_id', $depositoReal)
                                : $q->whereNull('deposito_id');
                        })
                        ->lockForUpdate()
                        ->first();

                    if ($filaReal) {
                        $componente = $filaReal;
                    } else {
                        $nueva = new ComponenteModel();
                        $nueva->nombre = $componente->nombre;
                        $nueva->tipo_id = $componente->tipo_id;
                        $nueva->deposito_id = $depositoReal;
                        $nueva->estado_id = ComponenteController::ESTADO_SIN_STOCK;
                        $nueva->stock = 0;
                        $nueva->save();
                        $componente = $nueva;
                    }
                }

                // 1) Ingreso de stock — siempre sobre una fila estado 4 o 7
                $componente->stock += $cantidad;
                $componente->estado_id = ComponenteController::ESTADO_DISPONIBLE;
                $componente->save();

                $hist = new HistoriaModel();
                $hist->tecnico = $user->name;
                $hist->detalle = 'Ingresó ' . $cantidad . ' unidad(es) al stock del tóner: '
                    . $componente->nombre
                    . ' (' . ($componente->deposito->nombre ?? 'sin depósito') . ')'
                    . ' para utilizarlo en la impresora: '
                    . $request->input('addIdentificador') . ' - ' . $request->input('addNombre') . '.';
                $hist->motivo  = $motivo;
                $hist->tipo_id = 4;
                $hist->save();

                // 2) 1 unidad pasa a En uso
                $impresora->toner_id = $componenteController->transferStateByPc(
                    $componente->id,
                    1,
                    ComponenteController::ESTADO_EN_USO,
                    '',
                    null,
                    null,
                    false,
                    false
                );

            // ── Modo registrar: tóner nuevo que no estaba en stock
            } elseif ($modo === 'registrar') {
                $nombre = trim($request->input('addToner_nombre', ''));
                $depositoOrigen  = $request->input('addToner_deposito_origen') ?: null;
                $cantidad = (int) $request->input('addToner_cantidad', 1);
                $motivo = trim($request->input('addToner_motivo', ''));

                if ($nombre === '') {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'addToner_nombre' => 'El nombre del tóner es obligatorio.',
                    ]);
                }
                if (! $depositoOrigen) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'addToner_deposito_origen' => 'El depósito de origen es obligatorio.',
                    ]);
                }

                // Tipo ID del Tóner (se busca por nombre; alternativa: hardcodearlo si lo conocés)
                $tipoToner = TipoComponenteModel::where('nombre', 'Toner')->first();
                $tipoId    = $tipoToner ? $tipoToner->id : null;

                // Crear registro "En uso" para la impresora
                $nuevo = new ComponenteModel();
                $nuevo->nombre = $nombre;
                $nuevo->tipo_id = $tipoId;
                $nuevo->estado_id = ComponenteController::ESTADO_EN_USO;
                $nuevo->deposito_id = null;
                $nuevo->deposito_origen_id = $depositoOrigen;
                $nuevo->stock = 1;
                $nuevo->save();

                $impresora->toner_id = $nuevo->id;

                // Si se registró más de 1 unidad, las restantes van al depósito como disponibles
                $cantidadDisponible = $cantidad - 1;
                if ($cantidadDisponible > 0) {
                    $stockDisponible = ComponenteModel::where('tipo_id', $tipoId)
                        ->whereRaw('LOWER(TRIM(nombre)) = ?', [mb_strtolower(trim($nombre))])
                        ->where('deposito_id', $depositoOrigen)
                        ->whereIn('estado_id', [
                            ComponenteController::ESTADO_DISPONIBLE,
                            ComponenteController::ESTADO_SIN_STOCK,
                        ])
                        ->first();

                    if ($stockDisponible) {
                        $stockDisponible->stock = (int) $stockDisponible->stock + $cantidadDisponible;
                        $stockDisponible->estado_id = ComponenteController::ESTADO_DISPONIBLE;
                        $stockDisponible->save();
                    } else {
                        $stockNuevo = new ComponenteModel();
                        $stockNuevo->nombre = $nombre;
                        $stockNuevo->tipo_id = $tipoId;
                        $stockNuevo->deposito_id = $depositoOrigen;
                        $stockNuevo->deposito_origen_id = null;
                        $stockNuevo->estado_id = ComponenteController::ESTADO_DISPONIBLE;
                        $stockNuevo->stock = $cantidadDisponible;
                        $stockNuevo->save();
                    }
                }

                $hist = new HistoriaModel();
                $hist->tecnico = $user->name;
                $hist->detalle = 'Registró el tóner "' . $nombre . '"'
                    . ' directamente en la impresora: '
                    . $request->input('addIdentificador') . ' - ' . $request->input('addNombre') . '.'
                    . ' Cantidad registrada: ' . $cantidad . '.';
                $hist->motivo  = $motivo !== '' ? $motivo : 'Carga de Impresora';
                $hist->tipo_id = 4;
                $hist->save();

            // ── Modo no-identificado
            } elseif ($modo === 'no-identificado') {
                $tipoToner = TipoComponenteModel::where('nombre', 'Toner')->first();
                $tipoId = $tipoToner ? $tipoToner->id : null;

                $noIdent = new ComponenteModel();
                $noIdent->nombre = 'Tóner no identificado';
                $noIdent->tipo_id = $tipoId;
                $noIdent->estado_id = ComponenteController::ESTADO_EN_USO;
                $noIdent->deposito_id = null;
                $noIdent->stock = 1;
                $noIdent->save();

                $impresora->toner_id = $noIdent->id;

                $hist = new HistoriaModel();
                $hist->tecnico = $user->name;
                $hist->detalle = 'Registró un tóner no identificado en la impresora: '
                    . $request->input('addIdentificador') . ' - ' . $request->input('addNombre') . '.'
                    . ($request->input('addToner_observaciones')
                        ? ' Observaciones: ' . $request->input('addToner_observaciones')
                        : '');
                $hist->motivo = 'Carga de Impresora';
                $hist->tipo_id = 4;
                $hist->save();

            // ── Modo stock (default): tóner desde el inventario
            } else {
                $impresora->toner_id = $componenteController->transferStateByPc(
                    $request->input('addToner'),
                    1,
                    ComponenteController::ESTADO_EN_USO,
                    '',
                    null,
                    null,
                    false,
                    false
                );
            }

            $impresora->save();

            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = 'Cargó la impresora: '
                . $request->input('addIdentificador') . ' - '
                . $request->input('addNombre') . ' - '
                . $request->input('addMarca') . '.';
            $historia->motivo = 'Carga de Impresora';
            $historia->componente_id    = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora';
            $historia->tipo_id = 7;
            $historia->save();
        });

        return redirect()->back()->with('success', 'Impresora guardada correctamente.');
    }

    public function edit(Request $request)
    {
        $user = Auth::user();
        $areaModel = new AreaModel();

        $id = $request->input('editId');
        $impresora = ImpresoraModel::find($id);

        if ($request->input('editDetalle') != null || $request->input('editDetalle') != '') {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = $request->input('editDetalle');
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora';
            $historia->tipo_id = 7;
            $historia->save();
        }

        // Cambio de tóner
        if ($impresora->toner_id != $request->input('editToner')) {
            DB::transaction(function () use ($impresora, $request, $user) {
                $historia = new HistoriaModel();
                $historia->tecnico = $user->name;
                $historia->detalle = 'Cambió el toner de la impresora: '
                    . $impresora->identificador . ' - ' . $impresora->nombre
                    . ' de ' . (ComponenteModel::find($impresora->toner_id)->nombre ?? 'toner no asignado')
                    . ' a ' . (ComponenteModel::find($request->input('editToner'))->nombre ?? 'sin nombre') . '.';
                $historia->motivo = $request->input('editMotivo');
                $historia->componente_id    = $impresora->id;
                $historia->tipo_dispositivo = 'Impresora';
                $historia->tipo_id = 7;
                $historia->save();

                $transferencia = new ComponenteController();
                $transferencia->consumirComponenteEnUso($impresora->toner_id, 1);
                $transferencia->transferStateByPc(
                    $request->input('editToner'),
                    1,
                    ComponenteController::ESTADO_EN_USO,
                    '',
                    null,
                    null,
                    false,
                    false
                );

                $impresora->toner_id = $request->input('editToner');
                $impresora->save();
            });
        }

        // Cambio de depósito
        if (
            $impresora->deposito_id != $request->input('editDeposito')
            && $request->input('editDeposito') != null
        ) {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = 'Cambió el depósito de la impresora: '
                . $impresora->identificador . ' - ' . $impresora->nombre
                . ' de ' . (DepositoModel::find($impresora->deposito_id)->nombre ?? 'depósito no asignado')
                . ' a '  . (DepositoModel::find($request->input('editDeposito'))->nombre ?? 'depósito no asignado')
                . (($area = AreaModel::find($impresora->area_id)) ? ', se quitó del área ' . ($area->nombre ?? '') : '') . '.';
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora';
            $historia->tipo_id = 7;
            $historia->save();

            $impresora->deposito_id = $request->input('editDeposito');
            $impresora->area_id = null;
        }

        // Cambio de área
        if (
            $impresora->area_id != $request->input('editArea')
            && $request->input('editArea') != null
        ) {
            $nombreArea = AreaModel::find($request->input('editArea'))->nombre;
            if ($request->input('editArea') == 27) {
                $nombreArea .= ' ' . ($request->input('editNroConsul') ?? '');
            }

            $detalleArea = 'Cambió el área de la impresora: '
                . $impresora->identificador . ' - ' . $impresora->nombre
                . ' de ' . (AreaModel::find($impresora->area_id)->nombre ?? 'área no asignada')
                . ' a ' . $nombreArea
                . (($deposito = DepositoModel::find($impresora->deposito_id))
                    ? ', se quitó del depósito ' . ($deposito->nombre ?? '')
                    : '') . '.';

            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = $detalleArea;
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora';
            $historia->tipo_id = 7;
            $historia->save();

            $areaExistente = $areaModel->findByName($nombreArea);
            if ($areaExistente) {
                $impresora->area_id = $areaExistente->id;
            } else {
                $areaNueva = new AreaModel();
                $areaNueva->nombre = $nombreArea;
                $areaNueva->visible = false;
                $areaNueva->save();
                $impresora->area_id = $areaNueva->id;
            }
            $impresora->deposito_id = null;
        }

        // Cambio de IP
        if ($impresora->ip != $request->input('editIp')) {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = 'Cambió la IP de la impresora: '
                . $impresora->identificador . ' - ' . $impresora->nombre
                . ' de ' . $impresora->ip . ' a ' . $request->input('editIp') . '.';
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora';
            $historia->tipo_id = 7;
            $historia->save();
        }
        $impresora->ip = $request->input('editIp');

        // Cambio de marca/modelo
        if ($impresora->marca_modelo != $request->input('editMarca')) {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = 'Cambió la marca y el modelo de la impresora: '
                . $impresora->identificador . ' - ' . $impresora->nombre
                . ' de ' . $impresora->marca_modelo . ' a ' . $request->input('editMarca') . '.';
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora';
            $historia->tipo_id = 7;
            $historia->save();
        }
        $impresora->marca_modelo = $request->input('editMarca');

        // Cambio de nombre
        if ($impresora->nombre != $request->input('editNombre')) {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = 'Cambió el nombre de la impresora: '
                . $impresora->identificador . ' - ' . $impresora->nombre
                . ' de ' . $impresora->nombre . ' a ' . $request->input('editNombre') . '.';
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora';
            $historia->tipo_id = 7;
            $historia->save();
        }
        $impresora->nombre = $request->input('editNombre');

        // Cambio de identificador
        if ($impresora->identificador != $request->input('editIdentificador')) {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = 'Cambió el identificador de la impresora: '
                . $impresora->identificador . ' - ' . $impresora->nombre
                . ' de ' . $impresora->identificador . ' a ' . $request->input('editIdentificador') . '.';
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora';
            $historia->tipo_id = 7;
            $historia->save();
        }
        $impresora->identificador = $request->input('editIdentificador');

        $impresora->update();

        return redirect()->back()->with('success', 'Impresora editada correctamente.');
    }

    public function delete(Request $request)
    {
        $user = Auth::user();
        $id = $request->input('deleteId');
        $impresora = ImpresoraModel::find($id);

        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = 'Eliminó la impresora: ' . $impresora->identificador . ' - ' . $impresora->nombre;
        $historia->motivo  = $request->input('removeMotivo');
        $historia->tipo_id = 7;
        $historia->save();

        $transferencia = new ComponenteController();
        $transferencia->transferStateByPc($impresora->toner_id, 1, 4, '', null, null, false, false);

        $impresora->delete();

        return redirect()->back()->with('success', 'Impresora eliminada correctamente.');
    }

    public function getHistoria($id)
    {
        $historias = HistoriaModel::where('componente_id', $id)->get();
        return response()->json(['historia' => $historias]);
    }
}
