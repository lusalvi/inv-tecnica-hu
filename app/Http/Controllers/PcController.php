<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComponenteModel;
use App\Models\AreaModel;
use App\Models\TipoComponenteModel;
use App\Models\DepositoModel;
use App\Models\HistoriaModel;
use App\Models\PcModel;
use App\Models\ComponentePcModel;
use App\Models\EstadoComponenteModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PcController extends Controller
{
    public function index()
    {
        $componentesModel = new ComponenteModel();

        $componentes = ComponenteModel::with(['tipo', 'deposito', 'estado'])->get();

        $motherboards = $componentesModel->getComponenteByTipoForPc('Placa madre', '');
        $procesadores = $componentesModel->getComponenteByTipoForPc('Procesador', '');
        $fuentes      = $componentesModel->getComponenteByTipoForPc('Fuente', '');
        $placasvid    = $componentesModel->getComponenteByTipoForPc('Placa de video', '');
        $rams         = $componentesModel->getComponenteByTipoForPc('RAM', '');
        $discos       = $componentesModel->getComponenteByTipoForPc('HDD', 'SDD');

        $motherboardsEnUso = $componentesModel->getComponenteByTipoBystate('Placa madre', '');
        $procesadoresEnUso = $componentesModel->getComponenteByTipoBystate('Procesador', '');
        $fuentesEnUso      = $componentesModel->getComponenteByTipoBystate('Fuente', '');
        $placasvidEnUso    = $componentesModel->getComponenteByTipoBystate('Placa de video', '');
        $ramsEnUso         = $componentesModel->getComponenteByTipoBystate('RAM', '');
        $discosEnUso       = $componentesModel->getComponenteByTipoBystate('HDD', 'SDD');

        $motherboardsWithoutStock = $componentesModel->getComponenteByTipoWithoutStock('Placa madre', '');
        $procesadoresWithoutStock = $componentesModel->getComponenteByTipoWithoutStock('Procesador', '');
        $fuentesWithoutStock      = $componentesModel->getComponenteByTipoWithoutStock('Fuente', '');
        $ramsWithoutStock         = $componentesModel->getComponenteByTipoWithoutStock('RAM', '');
        $discosWithoutStock       = $componentesModel->getComponenteByTipoWithoutStock('HDD', 'SDD');

        $pcs      = PcModel::with(['area', 'deposito', 'componentes.tipo'])->get();
        $historias = HistoriaModel::where('tipo_id', 5)
            ->orderBy('created_at', 'desc')
            ->get();
        $tipos    = TipoComponenteModel::all();
        $depositos = DepositoModel::all();
        $areas    = AreaModel::orderBy('nombre', 'asc')->get();

        return view('gest_pc', [
            'componentes'              => $componentes,
            'historias'                => $historias,
            'tipos'                    => $tipos,
            'depositos'                => $depositos,
            'areas'                    => $areas,
            'motherboards'             => $motherboards,
            'procesadores'             => $procesadores,
            'fuentes'                  => $fuentes,
            'rams'                     => $rams,
            'discos'                   => $discos,
            'motherboardsEnUso'        => $motherboardsEnUso,
            'procesadoresEnUso'        => $procesadoresEnUso,
            'fuentesEnUso'             => $fuentesEnUso,
            'ramsEnUso'                => $ramsEnUso,
            'discosEnUso'              => $discosEnUso,
            'motherboardsWithoutStock' => $motherboardsWithoutStock,
            'procesadoresWithoutStock' => $procesadoresWithoutStock,
            'fuentesWithoutStock'      => $fuentesWithoutStock,
            'ramsWithoutStock'         => $ramsWithoutStock,
            'discosWithoutStock'       => $discosWithoutStock,
            'pcs'                      => $pcs,
            'placasvidEnUso'           => $placasvidEnUso,
            'placasvid'                => $placasvid,
        ]);
    }


    public function store(Request $request)
    {
        $user      = Auth::user();
        $areaModel = new AreaModel();

        $request->validate([
            'addNombre'        => 'required|string|max:255|unique:pc,nombre',
            'addIdentificador' => 'required|string|max:255|unique:pc,identificador',
            'addEnUso'        => 'required|boolean',
            'addArea'         => 'nullable|required_if:addEnUso,1|exists:area,id',
            'addDeposito'     => 'nullable|required_if:addEnUso,0|exists:deposito,id',
            'discos1'          => 'nullable|array',
            'discos1.*'        => 'nullable|string|max:255',
            'discos1_nombre'   => 'nullable|array',
            'discos1_nombre.*' => 'nullable|string|max:255',
            'rams1'            => 'nullable|array',
            'rams1.*'          => 'nullable|string|max:255',
            'rams1_nombre'     => 'nullable|array',
            'rams1_nombre.*'   => 'nullable|string|max:255',
        ]);

        \DB::transaction(function () use ($request, $user, $areaModel) {

            $pc               = new PcModel();
            $pc->nombre       = $request->input('addNombre');
            $pc->identificador = $request->input('addIdentificador');
            $pc->ip           = $request->input('addIp');
            $pc->deposito_id  = $request->input('addDeposito');

            if (! $request->input('addDeposito')) {
                $nombreArea = AreaModel::find($request->input('addArea'))->nombre;
                if ($request->input('addNroConsul')) {
                    $nombreArea .= ' ' . $request->input('addNroConsul');
                }
                $area = $areaModel->findByName($nombreArea);
                if ($area) {
                    $pc->area_id = $area->id;
                } else {
                    $areaNueva          = new AreaModel();
                    $areaNueva->nombre  = $nombreArea;
                    $areaNueva->visible = false;
                    $areaNueva->save();
                    $pc->area_id = $areaNueva->id;
                }
            }
            $pc->save();

            $componenteController = new ComponenteController();

            /*
             * Modo "sin-stock": el usuario eligió un componente que existe pero
             * no tenía unidades disponibles, e indicó cuántas unidades ingresa.
             *
             *   1) Ingresa N unidades al registro existente (Sin Stock → Disponible).
             *   2) Toma 1 unidad para la PC a través de transferStateByPc,
             *      que es el mismo camino que usa el modo "stock":
             *        Disponible N → N-1 (o Sin Stock si queda en 0) y En uso +1.
             *
             * Devuelve el id del registro En uso que se vincula a la PC.
             */
            $reactivarSinStock = function (
                int $componenteId,
                int $cantidad,
                ?string $motivo,
                array $tiposPermitidos,
                string $campo
            ) use ($pc, $user, $componenteController) {

                $motivo = trim((string) $motivo);

                if ($cantidad < 1) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        $campo . '_cantidad' => 'La cantidad a ingresar debe ser un entero mayor o igual a 1.',
                    ]);
                }

                if ($motivo === '') {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        $campo . '_motivo' => 'El motivo del ingreso de stock es obligatorio.',
                    ]);
                }

                $componente = ComponenteModel::with('deposito')
                    ->whereKey($componenteId)
                    ->lockForUpdate()
                    ->first();

                if (! $componente) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        $campo => 'El componente seleccionado no existe.',
                    ]);
                }

                if (! in_array((int) $componente->tipo_id, $tiposPermitidos, true)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        $campo => 'El componente seleccionado no corresponde a este tipo de componente.',
                    ]);
                }

                // Se acepta Sin Stock (caso normal) o Disponible (por si otra fila del
                // mismo formulario, u otro usuario, ya repuso el mismo registro).
                if (! in_array((int) $componente->estado_id, [
                    ComponenteController::ESTADO_SIN_STOCK,
                    ComponenteController::ESTADO_DISPONIBLE,
                ], true)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        $campo => 'Solo se puede ingresar stock a un componente Sin Stock o Disponible.',
                    ]);
                }

                if (! $componente->deposito_id) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        $campo => 'El componente seleccionado no tiene un depósito asociado.',
                    ]);
                }

                // 1) Ingreso de stock.
                $componente->stock     = (int) $componente->stock + $cantidad;
                $componente->estado_id = ComponenteController::ESTADO_DISPONIBLE;
                $componente->save();

                $historia          = new HistoriaModel();
                $historia->tecnico = $user->name;
                $historia->detalle = 'Ingresó ' . $cantidad . ' unidad(es) al stock de: '
                    . $componente->nombre
                    . ' (' . ($componente->deposito->nombre ?? 'sin depósito') . ')'
                    . ' para utilizar una en la PC: ' . $pc->identificador . ' - ' . $pc->nombre . '.';
                $historia->motivo  = $motivo;
                $historia->tipo_id = 4;
                $historia->save();

                // 2) Una unidad pasa a En uso para la PC (deja el resto Disponible,
                //    o el registro en Sin Stock si N = 1).
                return $componenteController->transferStateByPc(
                    $componente->id,
                    1,
                    ComponenteController::ESTADO_EN_USO,
                    'Creación de PC',
                    $pc->identificador,
                    $pc->nombre,
                    true,
                    false
                );
            };

            $crearRegistrado = function (
                string $nombre,
                int $tipo_id,
                ?int $depositoOrigen = null,
                bool $noIdentificado = false,
                ?string $observaciones = null,
                int $cantidad = 1,
                ?string $motivo = null
            ) use ($pc, $user) {

                if ($cantidad < 1) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'cantidad' => 'La cantidad debe ser mayor o igual a 1.',
                    ]);
                }

                // Registrar nuevo exige depósito de origen: sin él, las unidades
                // sobrantes (cantidad - 1) no tendrían dónde ingresar y se perderían.
                if (! $noIdentificado && $depositoOrigen === null) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'deposito_origen' => 'El depósito de origen es obligatorio al registrar un componente nuevo.',
                    ]);
                }

                /*
     * Una unidad se asigna directamente a la PC.
     * Las unidades restantes quedan disponibles en el depósito de origen.
     */
                $nuevo = new ComponenteModel();
                $nuevo->nombre             = $nombre;
                $nuevo->tipo_id            = $tipo_id;
                $nuevo->estado_id          = ComponenteController::ESTADO_EN_USO;
                $nuevo->deposito_id        = null;
                $nuevo->deposito_origen_id = $depositoOrigen;
                $nuevo->stock              = 1;
                $nuevo->save();

                $componente_pc                = new ComponentePcModel();
                $componente_pc->pc_id         = $pc->id;
                $componente_pc->componente_id = $nuevo->id;
                $componente_pc->save();

                /*
     * Si se registraron más unidades de las que se asignaron a la PC,
     * las restantes ingresan como stock disponible en el depósito de origen.
     */
                $cantidadDisponible = $cantidad - 1;

                if ($depositoOrigen !== null) {

                    $stockDisponible = ComponenteModel::where('tipo_id', $tipo_id)
                        ->whereRaw('LOWER(TRIM(nombre)) = ?', [mb_strtolower(trim($nombre))])
                        ->where('deposito_id', $depositoOrigen)
                        ->whereIn('estado_id', [
                            ComponenteController::ESTADO_DISPONIBLE,
                            ComponenteController::ESTADO_SIN_STOCK,
                        ])
                        ->lockForUpdate()
                        ->first();

                    if ($stockDisponible) {

                        if ($cantidadDisponible > 0) {
                            $stockDisponible->stock = (int) $stockDisponible->stock + $cantidadDisponible;
                            $stockDisponible->estado_id = ComponenteController::ESTADO_DISPONIBLE;
                            $stockDisponible->save();
                        }
                    } else {

                        $stockDisponible = new ComponenteModel();
                        $stockDisponible->nombre      = $nombre;
                        $stockDisponible->tipo_id     = $tipo_id;
                        $stockDisponible->deposito_id = $depositoOrigen;
                        $stockDisponible->deposito_origen_id = null;

                        if ($cantidadDisponible > 0) {
                            $stockDisponible->estado_id = ComponenteController::ESTADO_DISPONIBLE;
                            $stockDisponible->stock     = $cantidadDisponible;
                        } else {
                            $stockDisponible->estado_id = ComponenteController::ESTADO_SIN_STOCK;
                            $stockDisponible->stock     = 0;
                        }

                        $stockDisponible->save();
                    }
                }

                $historia = new HistoriaModel();
                $historia->tecnico = $user->name;
                $historia->detalle = ($noIdentificado
                    ? 'Registró un componente no identificado ('
                    : 'Registró el componente "')
                    . $nombre
                    . ($noIdentificado ? ')' : '"')
                    . ' directamente en la PC: ' . $pc->identificador . ' - ' . $pc->nombre . '.'
                    . ' Cantidad registrada: ' . $cantidad . '.'
                    . ($observaciones ? ' Observaciones: ' . $observaciones : '');
                $motivo = trim((string) $motivo);
                $historia->motivo  = $motivo !== '' ? $motivo : 'Creación de PC';
                $historia->tipo_id = 4;
                $historia->save();

                return $nuevo->id;
            };

            $singulares = [
                ['campo' => 'addMotherboard', 'tipo_id' => 5, 'label' => 'Placa madre'],
                ['campo' => 'addProcesador',  'tipo_id' => 4, 'label' => 'Procesador'],
                ['campo' => 'addFuente',      'tipo_id' => 2, 'label' => 'Fuente'],
                ['campo' => 'addPlacavid',    'tipo_id' => 7, 'label' => 'Placa de video'],
            ];

            foreach ($singulares as $s) {
                $id_stock   = $request->input($s['campo']);
                $nombre_reg = trim($request->input($s['campo'] . '_nombre', ''));

                $modo = $request->input($s['campo'] . '_modo', $id_stock ? 'stock' : ($nombre_reg !== '' ? 'registrar' : null));

                if ($modo === 'stock' && $id_stock) {
                    $componente_pc               = new ComponentePcModel();
                    $componente_pc->pc_id        = $pc->id;
                    $componente_pc->componente_id = $componenteController->transferStateByPc(
                        $id_stock,
                        1,
                        ComponenteController::ESTADO_EN_USO,
                        'Creación de PC',
                        $pc->identificador,
                        $pc->nombre,
                        true,
                        false
                    );
                    $componente_pc->save();
                } elseif ($modo === 'registrar' && $nombre_reg !== '') {
                    $depositoOrigen = $request->input($s['campo'] . '_deposito_origen');
                    if ($depositoOrigen !== null && $depositoOrigen !== '') {
                        $depositoOrigen = (int) $depositoOrigen;
                        if (! DepositoModel::whereKey($depositoOrigen)->exists()) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                $s['campo'] . '_deposito_origen' => 'El depósito de origen seleccionado no existe.',
                            ]);
                        }
                    } else {
                        $depositoOrigen = null;
                    }
                    $crearRegistrado(
                        $nombre_reg,
                        $s['tipo_id'],
                        $depositoOrigen,
                        false,
                        $request->input($s['campo'] . '_observaciones'),
                        (int) $request->input($s['campo'] . '_cantidad', 1),
                        $request->input($s['campo'] . '_motivo')
                    );
                } elseif ($modo === 'sin-stock') {
                    if (! $id_stock) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            $s['campo'] => 'Seleccioná el componente al que se le ingresará stock.',
                        ]);
                    }
                    $componente_pc                = new ComponentePcModel();
                    $componente_pc->pc_id         = $pc->id;
                    $componente_pc->componente_id = $reactivarSinStock(
                        (int) $id_stock,
                        (int) $request->input($s['campo'] . '_cantidad', 0),
                        $request->input($s['campo'] . '_motivo'),
                        [$s['tipo_id']],
                        $s['campo']
                    );
                    $componente_pc->save();
                } elseif ($modo === 'no-identificada') {
                    $crearRegistrado(
                        $s['label'] . ' no identificada',
                        $s['tipo_id'],
                        null,
                        true,
                        $request->input($s['campo'] . '_observaciones')
                    );
                }
            }

            /*
             * Discos y RAM: cada fila del wizard trae su propio modo
             * (stock | sin-stock | registrar | no-identificada). Los arrays
             * <prefix>[], <prefix>_modo[], <prefix>_cantidad[], etc. están
             * alineados por índice. Antes se recorría <prefix>[] asumiendo que
             * toda fila con id era "stock", lo que ignoraba el modo sin-stock.
             */
            $procesarMulti = function (
                string $prefix,
                array $tiposPermitidos,
                int $tipoRegistrar,
                string $nombreNoIdentificado
            ) use ($request, $pc, $user, $componenteController, $crearRegistrado, $reactivarSinStock) {

                $ids           = (array) $request->input($prefix, []);
                $nombres       = (array) $request->input($prefix . '_nombre', []);
                $modos         = (array) $request->input($prefix . '_modo', []);
                $depositos     = (array) $request->input($prefix . '_deposito_origen', []);
                $observaciones = (array) $request->input($prefix . '_observaciones', []);
                $cantidades    = (array) $request->input($prefix . '_cantidad', []);
                $motivos       = (array) $request->input($prefix . '_motivo', []);

                $total     = max(count($ids), count($nombres), count($modos));
                $agrupados = [];

                for ($i = 0; $i < $total; $i++) {
                    $id     = $ids[$i] ?? null;
                    $nombre = trim((string) ($nombres[$i] ?? ''));
                    $modo   = $modos[$i] ?? ($id ? 'stock' : ($nombre !== '' ? 'registrar' : null));
                    $campo  = $prefix . '.' . $i;

                    if ($modo === 'stock' && $id) {
                        $componente = ComponenteModel::find($id);
                        if (! $componente) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                $campo => 'El componente seleccionado no existe.',
                            ]);
                        }

                        if (isset($agrupados[$componente->nombre])) {
                            $agrupados[$componente->nombre]['cantidad'] += 1;
                        } else {
                            $agrupados[$componente->nombre] = ['componente_id' => $id, 'cantidad' => 1];
                        }

                        $componente_pc                = new ComponentePcModel();
                        $componente_pc->pc_id         = $pc->id;
                        $componente_pc->componente_id = $componenteController->transferStateByPc(
                            $id,
                            1,
                            ComponenteController::ESTADO_EN_USO,
                            'Creación de PC',
                            $pc->identificador,
                            $pc->nombre,
                            false,
                            false
                        );
                        $componente_pc->save();
                    } elseif ($modo === 'sin-stock') {
                        if (! $id) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                $campo => 'Seleccioná el componente al que se le ingresará stock.',
                            ]);
                        }

                        // Ya registra su propia historia (ingreso + uso en la PC).
                        $componente_pc                = new ComponentePcModel();
                        $componente_pc->pc_id         = $pc->id;
                        $componente_pc->componente_id = $reactivarSinStock(
                            (int) $id,
                            (int) ($cantidades[$i] ?? 0),
                            $motivos[$i] ?? null,
                            $tiposPermitidos,
                            $campo
                        );
                        $componente_pc->save();
                    } elseif ($modo === 'registrar' && $nombre !== '') {
                        $depositoOrigen = $depositos[$i] ?? null;
                        $depositoOrigen = ($depositoOrigen !== null && $depositoOrigen !== '') ? (int) $depositoOrigen : null;
                        if ($depositoOrigen !== null && ! DepositoModel::whereKey($depositoOrigen)->exists()) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                $prefix . '_deposito_origen.' . $i => 'El depósito de origen seleccionado no existe.',
                            ]);
                        }
                        $crearRegistrado(
                            $nombre,
                            $tipoRegistrar,
                            $depositoOrigen,
                            false,
                            $observaciones[$i] ?? null,
                            (int) ($cantidades[$i] ?? 1),
                            $motivos[$i] ?? null
                        );
                    } elseif ($modo === 'no-identificada') {
                        $crearRegistrado(
                            $nombreNoIdentificado,
                            $tipoRegistrar,
                            null,
                            true,
                            $observaciones[$i] ?? null
                        );
                    }
                }

                foreach ($agrupados as $nombre => $datos) {
                    $historia          = new HistoriaModel();
                    $historia->tecnico = $user->name;
                    $historia->detalle = 'Usó ' . $datos['cantidad'] . ' ' . $nombre
                        . '/s para el armado de la PC: ' . $pc->identificador . ' - ' . $pc->nombre . '.';
                    $historia->motivo  = 'Creación de PC';
                    $historia->tipo_id = 4;
                    $historia->save();
                }
            };

            $procesarMulti('discos1', [3, 6], 3, 'Disco no identificado');
            $procesarMulti('rams1',   [1],    1, 'RAM no identificada');

            $historia                   = new HistoriaModel();
            $historia->tecnico          = $user->name;
            $historia->detalle          = 'Creó la PC: ' . $pc->identificador . ' - ' . $pc->nombre . '.';
            $historia->motivo           = 'Creación de PC';
            $historia->componente_id    = $pc->id;
            $historia->tipo_dispositivo = 'PC';
            $historia->tipo_id          = 5;
            $historia->save();
        });

        return redirect()->back()->with('success', 'PC guardada correctamente.');
    }


    // ─────────────────────────────────────────────────────────────────────
    // edit — envuelto en DB::transaction
    //
    // Cambios respecto al original:
    //   1. Todo el cuerpo está dentro de DB::transaction. Si cualquier
    //      operación falla, el inventario vuelve al estado anterior.
    //   2. Para los componentes singulares (placa madre, procesador,
    //      fuente, placa de video) se verifica explícitamente que el
    //      nuevo valor no sea null/"" antes de intentar el reemplazo.
    //      El original hacía ComponenteModel::find(null)->nombre en
    //      el mensaje de historia cuando el campo venía vacío, lo que
    //      producía un error fatal.
    //   3. Los IDs de estado se referencian a través de las constantes
    //      de ComponenteController en lugar de literales.
    // ─────────────────────────────────────────────────────────────────────
    public function edit(Request $request)
    {
        $user      = Auth::user();
        $areaModel = new AreaModel();

        $request->validate([
            'discos2'   => 'nullable|array',
            'discos2.*' => 'nullable|string|max:255',
            'rams2'     => 'nullable|array',
            'rams2.*'   => 'nullable|string|max:255',
        ]);

        $id = $request->input('editId');
        $pc = PcModel::find($id);

        $motherActual   = ComponentePcModel::getMotherboardIdByPc($id);
        $proceActual    = ComponentePcModel::getProcesadorIdByPc($id);
        $fuenteActual   = ComponentePcModel::getFuenteIdByPc($id);
        $placavidActual = ComponentePcModel::getPlacavidIdByPc($id);

        DB::transaction(function () use (
            $request,
            $user,
            $areaModel,
            $id,
            $pc,
            $motherActual,
            $proceActual,
            $fuenteActual,
            $placavidActual
        ) {
            $transferecia = new ComponenteController();

            // Retira una unidad de una PC respetando el origen. Si el componente
            // estaba En uso pero nunca tuvo deposito_origen_id, no lo convierte
            // en Disponible sin depósito: se elimina cuando deja de tener vínculos.
            $retirarComponenteDePc = function (int $componenteId) use ($id, $pc, $transferecia) {
                $componente = ComponenteModel::whereKey($componenteId)
                    ->lockForUpdate()
                    ->first();

                if (! $componente) {
                    return;
                }

                $esOrigenDesconocido = (
                    (int) $componente->estado_id === ComponenteController::ESTADO_EN_USO
                    && $componente->deposito_origen_id === null
                );

                $vinculo = ComponentePcModel::where('pc_id', $id)
                    ->where('componente_id', $componenteId)
                    ->first();

                if ($esOrigenDesconocido) {
                    $otrosVinculos = ComponentePcModel::where('componente_id', $componente->id)
                        ->where('pc_id', '!=', $id)
                        ->count();

                    if ((int) $componente->stock > 0) {
                        $componente->stock -= 1;
                    }

                    if ($componente->stock === 0 && $otrosVinculos === 0) {
                        $componente->delete();
                    } else {
                        $componente->save();
                    }
                } else {
                    // Retiramos primero el vínculo para que transferStateByPc
                    // pueda eliminar una fila En uso que quede sin unidades.
                    $vinculo?->delete();
                    $transferecia->transferStateByPc(
                        $componente->id,
                        1,
                        ComponenteController::ESTADO_DISPONIBLE,
                        '',
                        $pc->id,
                        $pc->nombre,
                        false,
                        false
                    );
                }

                if ($esOrigenDesconocido) {
                    $vinculo?->delete();
                }
            };

            // ── Historial libre ───────────────────────────────────────────
            if ($request->input('editDetalle') != null && $request->input('editDetalle') != '') {
                $historia                   = new HistoriaModel();
                $historia->tecnico          = $user->name;
                $historia->detalle          = $request->input('editDetalle');
                $historia->motivo           = $request->input('editMotivo');
                $historia->componente_id    = $pc->id;
                $historia->tipo_dispositivo = 'PC';
                $historia->tipo_id          = 5;
                $historia->save();
            }

            // ── Depósito ──────────────────────────────────────────────────
            if ($pc->deposito_id != $request->input('editDeposito') && $request->input('editDeposito') != null) {
                $historia                   = new HistoriaModel();
                $historia->tecnico          = $user->name;
                $historia->detalle          = 'Cambió el depósito de la PC: ' . $pc->identificador
                    . ' - ' . $pc->nombre
                    . ' de ' . (DepositoModel::find($pc->deposito_id)->nombre ?? 'depósito no asignado')
                    . ' a ' . DepositoModel::find($request->input('editDeposito'))->nombre
                    . (($area = AreaModel::find($pc->area_id)) ? ', se quitó del área ' . ($area->nombre ?? '') : '') . '.';
                $historia->motivo           = $request->input('editMotivo');
                $historia->componente_id    = $pc->id;
                $historia->tipo_dispositivo = 'PC';
                $historia->tipo_id          = 5;
                $historia->save();
                $pc->deposito_id = $request->input('editDeposito');
                $pc->area_id     = null;
            }

            // ── Área ──────────────────────────────────────────────────────
            if ($pc->area_id != $request->input('editArea') && $request->input('editArea') != null) {
                $area = $request->input('editArea') == 27
                    ? AreaModel::find($request->input('editArea'))->nombre . ' ' . ($request->input('editNroConsul') ?? '')
                    : AreaModel::find($request->input('editArea'))->nombre;

                $areaObj = $areaModel->findByName($area);
                if (! $areaObj) {
                    $areaNueva          = new AreaModel();
                    $areaNueva->nombre  = $area;
                    $areaNueva->visible = false;
                    $areaNueva->save();
                    $areaObj = $areaNueva;
                }

                $historia                   = new HistoriaModel();
                $historia->tecnico          = $user->name;
                $historia->detalle          = 'Cambió el área de la PC: ' . $pc->identificador
                    . ' - ' . $pc->nombre
                    . ' de ' . (AreaModel::find($pc->area_id)->nombre ?? 'área no asignada')
                    . ' a ' . $area
                    . (($deposito = DepositoModel::find($pc->deposito_id)) ? ', se quitó del depósito ' . ($deposito->nombre ?? '') : '') . '.';
                $historia->motivo           = $request->input('editMotivo');
                $historia->componente_id    = $pc->id;
                $historia->tipo_dispositivo = 'PC';
                $historia->tipo_id          = 5;
                $historia->save();

                $pc->area_id     = $areaObj->id;
                $pc->deposito_id = null;
            }

            // ── IP ────────────────────────────────────────────────────────
            if ($pc->ip != $request->input('editIp')) {
                $historia                   = new HistoriaModel();
                $historia->tecnico          = $user->name;
                $historia->detalle          = 'Cambió la IP de la PC: ' . $pc->identificador
                    . ' - ' . $pc->nombre . ' de ' . $pc->ip . ' a ' . $request->input('editIp') . '.';
                $historia->motivo           = $request->input('editMotivo');
                $historia->componente_id    = $pc->id;
                $historia->tipo_dispositivo = 'PC';
                $historia->tipo_id          = 5;
                $historia->save();
            }
            $pc->ip = $request->input('editIp');

            // ── Nombre ────────────────────────────────────────────────────
            if ($pc->nombre != $request->input('editNombre')) {
                $historia                   = new HistoriaModel();
                $historia->tecnico          = $user->name;
                $historia->detalle          = 'Cambió el nombre de la PC: ' . $pc->identificador
                    . ' de ' . $pc->nombre . ' a ' . $request->input('editNombre') . '.';
                $historia->motivo           = $request->input('editMotivo');
                $historia->componente_id    = $pc->id;
                $historia->tipo_dispositivo = 'PC';
                $historia->tipo_id          = 5;
                $historia->save();
            }
            $pc->nombre = $request->input('editNombre');

            // ── Identificador ─────────────────────────────────────────────
            if ($pc->identificador != $request->input('editIdentificador')) {
                $historia                   = new HistoriaModel();
                $historia->tecnico          = $user->name;
                $historia->detalle          = 'Cambió el N.º de inventario de la PC de '
                    . $pc->identificador . ' a ' . $request->input('editIdentificador') . '.';
                $historia->motivo           = $request->input('editMotivo');
                $historia->componente_id    = $pc->id;
                $historia->tipo_dispositivo = 'PC';
                $historia->tipo_id          = 5;
                $historia->save();
            }
            $pc->identificador = $request->input('editIdentificador');
            $pc->update();

            // ── Componentes singulares (placa madre, procesador, fuente, placa de video) ──
            // Se compara el ID actual con el ID nuevo. Si son distintos Y el nuevo
            // no es null/vacío, se hace el reemplazo. Un campo vacío significa
            // "no cambiar", no "quitar".
            $singulares = [
                [
                    'campo'   => 'editMotherboard',
                    'actual'  => $motherActual,
                    'label'   => 'placa madre',
                ],
                [
                    'campo'   => 'editProcesador',
                    'actual'  => $proceActual,
                    'label'   => 'procesador',
                ],
                [
                    'campo'   => 'editFuente',
                    'actual'  => $fuenteActual,
                    'label'   => 'fuente',
                ],
                [
                    'campo'   => 'editPlacavid',
                    'actual'  => $placavidActual,
                    'label'   => 'placa de video',
                ],
            ];

            foreach ($singulares as $s) {
                $nuevoId = $request->input($s['campo']);

                // Si no hay nuevo valor, no hay nada que cambiar.
                if (! $nuevoId) {
                    continue;
                }

                // Si el actual es null o es distinto del nuevo, realizar el reemplazo.
                if ($s['actual'] != $nuevoId) {
                    $nombreActual = $s['actual']
                        ? (ComponenteModel::find($s['actual'])->nombre ?? 'no asignado')
                        : 'no asignado';
                    $nombreNuevo = ComponenteModel::find($nuevoId)->nombre;

                    $historia                   = new HistoriaModel();
                    $historia->tecnico          = $user->name;
                    $historia->detalle          = 'Cambió la ' . $s['label'] . ' de la PC: '
                        . $pc->identificador . ' - ' . $pc->nombre
                        . ' de "' . $nombreActual . '" a "' . $nombreNuevo . '".';
                    $historia->motivo           = $request->input('editMotivo');
                    $historia->componente_id    = $pc->id;
                    $historia->tipo_dispositivo = 'PC';
                    $historia->tipo_id          = 5;
                    $historia->save();

                    // Retirar el componente anterior respetando su origen.
                    if ($s['actual']) {
                        $retirarComponenteDePc((int) $s['actual']);
                    }

                    // Asignar el nuevo.
                    $componente_pc               = new ComponentePcModel();
                    $componente_pc->pc_id        = $pc->id;
                    $componente_pc->componente_id = $transferecia->transferStateByPc(
                        $nuevoId,
                        1,
                        ComponenteController::ESTADO_EN_USO,
                        '',
                        $pc->id,
                        $pc->nombre,
                        true,
                        true
                    );
                    $componente_pc->save();
                }
            }

            // ── Discos ────────────────────────────────────────────────────
            $discosActuales      = ComponentePcModel::getDiscosByPc($id);
            $discosSeleccionados = $request->input('discos2', []);

            $conteoDiscosActuales      = array_count_values($discosActuales);
            $conteoDiscosSeleccionados = array_count_values($discosSeleccionados);

            $discosNuevos              = [];
            $discosEliminados          = [];
            $discosAgrupados           = [];
            $discosAgrupadosEliminados = [];

            foreach ($conteoDiscosActuales as $disco => $count) {
                $diff = $count - ($conteoDiscosSeleccionados[$disco] ?? 0);
                for ($i = 0; $i < $diff; $i++) {
                    $discosEliminados[] = $disco;
                }
            }
            foreach ($conteoDiscosSeleccionados as $disco => $count) {
                $diff = $count - ($conteoDiscosActuales[$disco] ?? 0);
                for ($i = 0; $i < $diff; $i++) {
                    $discosNuevos[] = $disco;
                }
            }

            foreach ($discosNuevos as $disco) {
                $componente = ComponenteModel::find($disco);
                $discosAgrupados[$componente->nombre]['cantidad'] = ($discosAgrupados[$componente->nombre]['cantidad'] ?? 0) + 1;
                $discosAgrupados[$componente->nombre]['componente_id'] = $disco;

                $componente_pc               = new ComponentePcModel();
                $componente_pc->pc_id        = $pc->id;
                $componente_pc->componente_id = $transferecia->transferStateByPc(
                    $disco,
                    1,
                    ComponenteController::ESTADO_EN_USO,
                    '',
                    $pc->id,
                    $pc->nombre,
                    false,
                    false
                );
                $componente_pc->save();
            }

            foreach ($discosEliminados as $disco) {
                $componente = ComponenteModel::find($disco);
                $discosAgrupadosEliminados[$componente->nombre]['cantidad'] = ($discosAgrupadosEliminados[$componente->nombre]['cantidad'] ?? 0) + 1;
                $discosAgrupadosEliminados[$componente->nombre]['componente_id'] = $disco;

                $retirarComponenteDePc((int) $disco);
            }

            foreach ($discosAgrupados as $nombre => $datos) {
                $historia          = new HistoriaModel();
                $historia->tecnico = $user->name;
                $historia->detalle = 'Usó ' . $datos['cantidad'] . ' ' . $nombre
                    . '/s en el mantenimiento de la PC: ' . $pc->identificador . ' - ' . $pc->nombre . '.';
                $historia->motivo  = 'Mantenimiento de PC';
                $historia->tipo_id = 4;
                $historia->save();

                $historia                   = new HistoriaModel();
                $historia->tecnico          = $user->name;
                $historia->detalle          = 'Agregó ' . $datos['cantidad'] . ' ' . $nombre
                    . '/s en el mantenimiento de la PC: ' . $pc->identificador . ' - ' . $pc->nombre . '.';
                $historia->motivo           = $request->input('editMotivo');
                $historia->componente_id    = $request->input('editId');
                $historia->tipo_id          = 5;
                $historia->save();
            }

            foreach ($discosAgrupadosEliminados as $nombre => $datos) {
                $historia          = new HistoriaModel();
                $historia->tecnico = $user->name;
                $historia->detalle = 'Desocupó ' . $datos['cantidad'] . ' ' . $nombre
                    . '/s en el mantenimiento de la PC: ' . $pc->identificador . ' - ' . $pc->nombre . '.';
                $historia->motivo  = 'Mantenimiento de PC';
                $historia->tipo_id = 4;
                $historia->save();

                $historia                   = new HistoriaModel();
                $historia->tecnico          = $user->name;
                $historia->detalle          = 'Eliminó ' . $datos['cantidad'] . ' ' . $nombre
                    . '/s en el mantenimiento de la PC: ' . $pc->identificador . ' - ' . $pc->nombre . '.';
                $historia->motivo           = $request->input('editMotivo');
                $historia->componente_id    = $request->input('editId');
                $historia->tipo_id          = 5;
                $historia->save();
            }

            // ── RAMs ──────────────────────────────────────────────────────
            $ramsActuales      = ComponentePcModel::getRamsByPc($id);
            $ramsSeleccionados = $request->input('rams2', []);

            $conteoRamsActuales      = array_count_values($ramsActuales);
            $conteoRamsSeleccionados = array_count_values($ramsSeleccionados);

            $ramsNuevos              = [];
            $ramsEliminados          = [];
            $ramsAgrupados           = [];
            $ramsAgrupadosEliminados = [];

            foreach ($conteoRamsActuales as $ram => $count) {
                $diff = $count - ($conteoRamsSeleccionados[$ram] ?? 0);
                for ($i = 0; $i < $diff; $i++) {
                    $ramsEliminados[] = $ram;
                }
            }
            foreach ($conteoRamsSeleccionados as $ram => $count) {
                $diff = $count - ($conteoRamsActuales[$ram] ?? 0);
                for ($i = 0; $i < $diff; $i++) {
                    $ramsNuevos[] = $ram;
                }
            }

            foreach ($ramsNuevos as $ram) {
                $componente = ComponenteModel::find($ram);
                $ramsAgrupados[$componente->nombre]['cantidad'] = ($ramsAgrupados[$componente->nombre]['cantidad'] ?? 0) + 1;
                $ramsAgrupados[$componente->nombre]['componente_id'] = $ram;

                $componente_pc               = new ComponentePcModel();
                $componente_pc->pc_id        = $pc->id;
                $componente_pc->componente_id = $transferecia->transferStateByPc(
                    $ram,
                    1,
                    ComponenteController::ESTADO_EN_USO,
                    '',
                    $pc->id,
                    $pc->nombre,
                    false,
                    false
                );
                $componente_pc->save();
            }

            foreach ($ramsEliminados as $ram) {
                $componente = ComponenteModel::find($ram);
                $ramsAgrupadosEliminados[$componente->nombre]['cantidad'] = ($ramsAgrupadosEliminados[$componente->nombre]['cantidad'] ?? 0) + 1;
                $ramsAgrupadosEliminados[$componente->nombre]['componente_id'] = $ram;

                $retirarComponenteDePc((int) $ram);
            }

            foreach ($ramsAgrupados as $nombre => $datos) {
                $historia          = new HistoriaModel();
                $historia->tecnico = $user->name;
                $historia->detalle = 'Usó ' . $datos['cantidad'] . ' ' . $nombre
                    . '/s en el mantenimiento de la PC: ' . $pc->identificador . ' - ' . $pc->nombre . '.';
                $historia->motivo  = 'Mantenimiento de PC';
                $historia->tipo_id = 4;
                $historia->save();

                $historia                   = new HistoriaModel();
                $historia->tecnico          = $user->name;
                $historia->detalle          = 'Agregó ' . $datos['cantidad'] . ' ' . $nombre
                    . '/s en el mantenimiento de la PC: ' . $pc->identificador . ' - ' . $pc->nombre . '.';
                $historia->motivo           = $request->input('editMotivo');
                $historia->componente_id    = $request->input('editId');
                $historia->tipo_id          = 5;
                $historia->save();
            }

            foreach ($ramsAgrupadosEliminados as $nombre => $datos) {
                $historia          = new HistoriaModel();
                $historia->tecnico = $user->name;
                $historia->detalle = 'Desocupó ' . $datos['cantidad'] . ' ' . $nombre
                    . '/s en el mantenimiento de la PC: ' . $pc->identificador . ' - ' . $pc->nombre . '.';
                $historia->motivo  = 'Mantenimiento de PC';
                $historia->tipo_id = 4;
                $historia->save();

                $historia                   = new HistoriaModel();
                $historia->tecnico          = $user->name;
                $historia->detalle          = 'Eliminó ' . $datos['cantidad'] . ' ' . $nombre
                    . '/s en el mantenimiento de la PC: ' . $pc->identificador . ' - ' . $pc->nombre . '.';
                $historia->motivo           = $request->input('editMotivo');
                $historia->componente_id    = $request->input('editId');
                $historia->tipo_id          = 5;
                $historia->save();
            }
        }); // fin DB::transaction

        return redirect()->back()->with('success', 'PC editada correctamente.');
    }


    // ─────────────────────────────────────────────────────────────────────
    // delete
    //
    // Cambios respecto al original:
    //
    // Un componente puede llegar a esta operación en dos situaciones:
    //
    //   A) Vino del stock: estado_id = 5, stock >= 1, deposito_origen_id
    //      apunta a algún depósito real. transferStateByPc(..., DISPONIBLE)
    //      lo devuelve correctamente al depósito de origen.
    //
    //   B) Fue registrado directamente sobre la PC: estado_id = 5,
    //      deposito_origen_id = NULL. Nunca pasó por el inventario normal.
    //      Estas filas representan una unidad instalada (stock = 1 en su
    //      creación) pero no tienen una ubicación de origen conocida.
    //
    // Para el caso B no se inventa un depósito: se descuenta la unidad y,
    // si ya no quedan vínculos, se elimina la fila. Si comparte fila con
    // otra PC, se conserva con el stock restante.
    //
    // Toda la operación ocurre dentro de DB::transaction.
    // ─────────────────────────────────────────────────────────────────────
    public function delete(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'deleteId' => 'required|integer|exists:pc,id',
            'removeMotivo' => 'required|string|max:1000',
        ]);

        $id = (int) $request->input('deleteId');

        DB::transaction(function () use ($id, $user, $request) {
            $pc = PcModel::whereKey($id)->lockForUpdate()->first();

            if (! $pc) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'deleteId' => 'La PC seleccionada no existe.',
                ]);
            }

            $actualComps = ComponentePcModel::where('pc_id', $id)->get();

            foreach ($actualComps as $actualComp) {
                $componente = ComponenteModel::whereKey($actualComp->componente_id)
                    ->lockForUpdate()
                    ->first();

                if (! $componente) {
                    continue;
                }

                $esRegistradoDirectamente = (
                    (int) $componente->estado_id === ComponenteController::ESTADO_EN_USO
                    && $componente->deposito_origen_id === null
                );

                if ($esRegistradoDirectamente) {
                    // Un componente registrado directamente no tiene un depósito
                    // de origen conocido. Al eliminar la PC no se transforma en
                    // Disponible con deposito_id = NULL: se retira del inventario.
                    $otrosVinculos = ComponentePcModel::where('componente_id', $componente->id)
                        ->where('pc_id', '!=', $id)
                        ->count();

                    if ((int) $componente->stock > 0) {
                        $componente->stock -= 1;
                    }

                    if ($componente->stock === 0 && $otrosVinculos === 0) {
                        $componente->delete();
                    } else {
                        $componente->save();
                    }

                    $historia = new HistoriaModel();
                    $historia->tecnico = $user->name;
                    $historia->detalle = 'Retiró el componente registrado directamente "'
                        . $componente->nombre . '" al eliminar la PC: '
                        . $pc->identificador . ' - ' . $pc->nombre . '.';
                    $historia->motivo  = 'Eliminación de PC';
                    $historia->tipo_id = 4;
                    $historia->save();
                } else {
                    // Si provino del stock, se devuelve exactamente al depósito
                    // guardado en deposito_origen_id. Quitamos primero el vínculo
                    // para permitir limpiar la fila En uso si queda sin unidades.
                    ComponentePcModel::where('pc_id', $id)
                        ->where('componente_id', $componente->id)
                        ->first()?->delete();

                    (new ComponenteController())->transferStateByPc(
                        $componente->id,
                        1,
                        ComponenteController::ESTADO_DISPONIBLE,
                        '',
                        $pc->identificador,
                        $pc->nombre,
                        false,
                        false
                    );

                    $historia = new HistoriaModel();
                    $historia->tecnico = $user->name;
                    $historia->detalle = 'Desocupó 1 ' . $componente->nombre
                        . ' de la PC: ' . $pc->identificador . ' - ' . $pc->nombre . '.';
                    $historia->motivo  = 'Eliminación de PC';
                    $historia->tipo_id = 4;
                    $historia->save();
                }
            }

            ComponentePcModel::where('pc_id', $id)->delete();

            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = 'Eliminó la PC: ' . $pc->identificador . ' - ' . $pc->nombre . '.';
            $historia->motivo = $request->input('removeMotivo');
            $historia->tipo_id = 5;
            $historia->save();

            $pc->delete();
        });

        return redirect()->back()->with('success', 'PC eliminado correctamente.');
    }

    public function getHistoria($tipo, $id)
    {
        $historias = HistoriaModel::where('componente_id', $id)
            ->where('tipo_dispositivo', $tipo)
            ->get();
        Log::info($historias);
        return response()->json(['historia' => $historias]);
    }
}