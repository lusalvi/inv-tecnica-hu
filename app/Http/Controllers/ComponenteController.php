<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComponenteModel;
use App\Models\TipoComponenteModel;
use App\Models\DepositoModel;
use App\Models\HistoriaModel;
use App\Models\ComponentePcModel;
use App\Models\EstadoComponenteModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ComponenteController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────
    // IDs de estado: constantes centralizadas para no hardcodear en el código.
    // Si en el futuro los IDs cambian, se actualiza aquí solamente.
    // ─────────────────────────────────────────────────────────────────────
    const ESTADO_ROTO       = 2;
    const ESTADO_DISPONIBLE = 4;
    const ESTADO_EN_USO     = 5;
    const ESTADO_SIN_STOCK  = 7;

    // Transiciones permitidas desde "Cambiar estado" (UI de gest_componentes).
    // En uso (5) no aparece aquí: las transiciones que involucran En uso
    // solo ocurren a través de operaciones de PC (transferStateByPc /
    // retirarComponenteRoto).
    const TRANSICIONES_PERMITIDAS = [
        self::ESTADO_DISPONIBLE => [self::ESTADO_ROTO],
        self::ESTADO_ROTO       => [self::ESTADO_DISPONIBLE],
    ];


    // ─────────────────────────────────────────────────────────────────────
    // index
    // ─────────────────────────────────────────────────────────────────────
    public function index()
    {
        $componentes = ComponenteModel::with(['tipo', 'deposito', 'depositoOrigen', 'estado'])->get();

        // La tabla interna puede contener varias filas para el mismo componente:
        // una por estado y/o por depósito. La vista no debe exponer esa estructura.
        // Para En uso, el depósito conceptual es deposito_origen_id; para el resto
        // es el depósito físico actual (deposito_id).
        $depositos = DepositoModel::all();
        $componentesAgrupados = $componentes
            ->groupBy(function ($componente) {
                $depositoConceptual = (int) $componente->estado_id === self::ESTADO_EN_USO
                    ? $componente->deposito_origen_id
                    : $componente->deposito_id;

                return implode('|', [
                    (int) $componente->tipo_id,
                    mb_strtolower(trim((string) $componente->nombre)),
                    $depositoConceptual === null ? 'null' : (string) $depositoConceptual,
                ]);
            })
            ->map(function ($filas) {
                $primero = $filas->first();
                $porEstado = $filas->groupBy('estado_id');

                $disponible = (int) $filas
                    ->where('estado_id', self::ESTADO_DISPONIBLE)
                    ->sum('stock');
                $enUso = (int) $filas
                    ->where('estado_id', self::ESTADO_EN_USO)
                    ->sum('stock');
                $roto = (int) $filas
                    ->where('estado_id', self::ESTADO_ROTO)
                    ->sum('stock');

                $depositoConceptual = $primero->estado_id == self::ESTADO_EN_USO
                    ? $primero->deposito_origen_id
                    : $primero->deposito_id;

                return [
                    'tipo_id' => $primero->tipo_id,
                    'tipo_nombre' => $primero->tipo->nombre ?? 'Sin categoría',
                    'nombre' => $primero->nombre,
                    'deposito_id' => $depositoConceptual,
                    'deposito_nombre' => $primero->estado_id == self::ESTADO_EN_USO
                        ? ($primero->depositoOrigen->nombre ?? 'No asignado')
                        : ($primero->deposito->nombre ?? 'No asignado'),
                    'disponible' => $disponible,
                    'en_uso' => $enUso,
                    'roto' => $roto,
                    'total' => $disponible + $enUso + $roto,
                    'filas' => $filas,
                    'acciones' => [
                        'disponible' => optional(
                            $filas
                                ->where('estado_id', self::ESTADO_DISPONIBLE)
                                ->where('stock', '>', 0)
                                ->first()
                        )->id,

                        'roto' => optional(
                            $filas
                                ->where('estado_id', self::ESTADO_ROTO)
                                ->where('stock', '>', 0)
                                ->first()
                        )->id,
                    ],
                ];
            })
            ->values();

        $historias   = HistoriaModel::where('tipo_id', 4)
            ->orderBy('created_at', 'desc')
            ->get();
        $tipos     = TipoComponenteModel::all();
        $nombresDepositos = DepositoModel::pluck('nombre');

        // Excluir el estado En uso (5) y Sin Stock (7) del selector de
        // "Cambiar estado", ya que esas transiciones tienen sus propias
        // operaciones. Tampoco mostramos el estado 6 (eliminado).
        $estadosParaCambio = EstadoComponenteModel::whereIn('id', [
            self::ESTADO_ROTO,
            self::ESTADO_DISPONIBLE,
        ])->get();

        // Todos los estados (para otros filtros/visualizaciones).
        $estados = EstadoComponenteModel::whereNotIn('id', [6])->get();

        return view('gest_componentes', [
            'estados'          => $estados,
            'estadosParaCambio' => $estadosParaCambio,
            'componentes'      => $componentes,
            'componentesAgrupados' => $componentesAgrupados,
            'historias'        => $historias,
            'tipos'            => $tipos,
            'depositos'        => $depositos,
            'nombresDepositos' => $nombresDepositos,
        ]);
    }


    // ─────────────────────────────────────────────────────────────────────
    // store — nuevo componente
    // ─────────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'addNombre'   => 'required|string|max:255|unique:componente,nombre',
            'addTipo'     => 'required|integer',
            'addDeposito' => 'required|integer',
        ]);

        $componente = new ComponenteModel();
        $componente->nombre      = $request->input('addNombre');
        $componente->tipo_id     = $request->input('addTipo');
        $componente->deposito_id = $request->input('addDeposito');
        $componente->save();

        $historia          = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = 'Creó el componente: ' . $request->input('addNombre') . '.';
        $historia->motivo  = 'Creación de componente.';
        $historia->tipo_id = 4;
        $historia->save();

        return redirect()->back()->with('success', 'Componente guardado correctamente.');
    }


    // ─────────────────────────────────────────────────────────────────────
    // edit — editar nombre / categoría
    // ─────────────────────────────────────────────────────────────────────
    public function edit(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'editId'     => 'required|integer|exists:componente,id',
            'editNombre' => 'required|string|max:255',
            'editTipo'   => 'required|integer|exists:tipo_componente,id',
            'editMotivo' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $user) {
            $id = (int) $request->input('editId');

            // Buscamos la fila que representa al grupo que se está editando.
            $componente = ComponenteModel::whereKey($id)
                ->lockForUpdate()
                ->first();

            if (! $componente) {
                throw ValidationException::withMessages([
                    'editId' => 'El componente seleccionado no existe.',
                ]);
            }

            $nombreAnterior = $componente->nombre;
            $tipoAnterior   = $componente->tipo_id;

            // El depósito conceptual depende del estado:
            // - En uso → deposito_origen_id
            // - Resto   → deposito_id
            $depositoConceptual = (int) $componente->estado_id === self::ESTADO_EN_USO
                ? $componente->deposito_origen_id
                : $componente->deposito_id;

            // Buscamos todas las filas que forman el mismo grupo visual.
            $filasGrupo = ComponenteModel::where('tipo_id', $componente->tipo_id)
                ->whereRaw('LOWER(TRIM(nombre)) = ?', [
                    mb_strtolower(trim((string) $componente->nombre)),
                ])
                ->where(function ($query) use ($depositoConceptual) {
                    // Para En uso, el depósito conceptual está en
                    // deposito_origen_id.
                    //
                    // Para Disponible/Roto/Sin Stock, está en deposito_id.
                    $query
                        ->where(function ($q) use ($depositoConceptual) {
                            $q->whereIn('estado_id', [
                                self::ESTADO_DISPONIBLE,
                                self::ESTADO_ROTO,
                                self::ESTADO_SIN_STOCK,
                            ]);

                            if ($depositoConceptual === null) {
                                $q->whereNull('deposito_id');
                            } else {
                                $q->where('deposito_id', $depositoConceptual);
                            }
                        })
                        ->orWhere(function ($q) use ($depositoConceptual) {
                            $q->where('estado_id', self::ESTADO_EN_USO);

                            if ($depositoConceptual === null) {
                                $q->whereNull('deposito_origen_id');
                            } else {
                                $q->where('deposito_origen_id', $depositoConceptual);
                            }
                        });
                })
                ->lockForUpdate()
                ->get();

            if ($filasGrupo->isEmpty()) {
                throw ValidationException::withMessages([
                    'editId' => 'No se encontraron las filas del grupo seleccionado.',
                ]);
            }

            // Cambiamos nombre y categoría en todas las filas del grupo.
            foreach ($filasGrupo as $fila) {
                $fila->nombre  = $request->input('editNombre');
                $fila->tipo_id = $request->input('editTipo');
                $fila->save();
            }

            // Registramos una sola entrada de historial por la operación completa.
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->tipo_id = 4;
            $historia->motivo  = $request->input('editMotivo');

            $nombreNuevo = $request->input('editNombre');
            $tipoNuevo   = (int) $request->input('editTipo');

            if ($nombreAnterior !== $nombreNuevo && $tipoAnterior !== $tipoNuevo) {
                $tipoAnteriorNombre = TipoComponenteModel::find($tipoAnterior)->nombre ?? 'no asignado';
                $tipoNuevoNombre = TipoComponenteModel::find($tipoNuevo)->nombre ?? 'no asignado';

                $historia->detalle = 'Editó el nombre del componente: ' . $nombreAnterior
                    . ' a: ' . $nombreNuevo
                    . ', y la categoría de: ' . $tipoAnteriorNombre
                    . ' a ' . $tipoNuevoNombre . '.';
            } elseif ($nombreAnterior !== $nombreNuevo) {
                $historia->detalle = 'Editó el nombre del componente: ' . $nombreAnterior
                    . ' a: ' . $nombreNuevo . '.';
            } elseif ($tipoAnterior !== $tipoNuevo) {
                $tipoAnteriorNombre = TipoComponenteModel::find($tipoAnterior)->nombre ?? 'no asignado';
                $tipoNuevoNombre    = TipoComponenteModel::find($tipoNuevo)->nombre ?? 'no asignado';

                $historia->detalle = 'Editó la categoría del componente: ' . $nombreAnterior
                    . ' de ' . $tipoAnteriorNombre
                    . ' a ' . $tipoNuevoNombre . '.';
            } else {
                $historia->detalle = 'Editó el componente: ' . $nombreAnterior . '.';
            }

            $historia->save();
        });

        return redirect()->back()->with('success', 'Componente editado correctamente.');
    }


    // ─────────────────────────────────────────────────────────────────────
    // add_stock
    // ─────────────────────────────────────────────────────────────────────
    public function add_stock(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'editAddStock' => 'required|integer|min:1',
            'editNombreStock' => 'required|integer|exists:componente,id',
        ]);

        $cantidad = (int) $request->input('editAddStock');

        DB::transaction(function () use ($request, $user, $cantidad) {
            $componente = ComponenteModel::whereKey($request->input('editNombreStock'))
                ->lockForUpdate()
                ->first();

            if (! $componente) {
                throw ValidationException::withMessages([
                    'editNombreStock' => 'El componente seleccionado no existe.',
                ]);
            }

            if (! in_array((int) $componente->estado_id, [self::ESTADO_DISPONIBLE, self::ESTADO_SIN_STOCK], true)) {
                throw ValidationException::withMessages([
                    'editNombreStock' => 'Solo se puede agregar stock a un componente Disponible o Sin Stock.',
                ]);
            }

            $componente->stock += $cantidad;
            $componente->estado_id = self::ESTADO_DISPONIBLE;
            $componente->save();

            $historia          = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = 'Agregó ' . $cantidad
                . ' componente/s al stock de: ' . $componente->nombre . '.';
            $historia->motivo  = $request->input('editAddStockMotivo');
            $historia->tipo_id = 4;
            $historia->save();
        });

        return redirect()->back()->with('success', 'Stock agregado correctamente.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // remove_stock
    // ─────────────────────────────────────────────────────────────────────
    public function remove_stock(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'removeStock' => 'required|integer|min:1',
            'removeNombreStock' => 'required|integer|exists:componente,id',
        ]);

        $cantidad = (int) $request->input('removeStock');

        DB::transaction(function () use ($request, $user, $cantidad) {
            $componente = ComponenteModel::whereKey($request->input('removeNombreStock'))
                ->lockForUpdate()
                ->first();

            if (! $componente) {
                throw ValidationException::withMessages([
                    'removeNombreStock' => 'El componente seleccionado no existe.',
                ]);
            }

            if ((int) $componente->estado_id !== self::ESTADO_DISPONIBLE) {
                throw ValidationException::withMessages([
                    'removeNombreStock' => 'Solo se puede quitar stock de un componente Disponible.',
                ]);
            }

            if ((int) $componente->stock < $cantidad) {
                throw ValidationException::withMessages([
                    'removeStock' => 'La cantidad a quitar supera el stock disponible.',
                ]);
            }

            $componente->stock -= $cantidad;
            if ($componente->stock === 0) {
                $componente->estado_id = self::ESTADO_SIN_STOCK;
            }
            $componente->save();

            $historia          = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = 'Eliminó ' . $cantidad
                . ' componente/s del stock de: ' . $componente->nombre . '.';
            $historia->motivo  = $request->input('removeStockMotivo');
            $historia->tipo_id = 4;
            $historia->save();
        });

        return redirect()->back()->with('success', 'Stock eliminado correctamente.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // delete
    // ─────────────────────────────────────────────────────────────────────
    public function delete(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'deleteId'     => 'required|integer|exists:componente,id',
            'removeMotivo' => 'required|string|max:1000',
        ]);

        $id = (int) $request->input('deleteId');

        DB::transaction(function () use ($id, $user, $request) {
            // La fila recibida solamente identifica el grupo visual
            // que se quiere eliminar.
            $componente = ComponenteModel::whereKey($id)
                ->lockForUpdate()
                ->first();

            if (! $componente) {
                throw ValidationException::withMessages([
                    'deleteId' => 'El componente seleccionado no existe.',
                ]);
            }

            $nombre = $componente->nombre;
            $tipoId = $componente->tipo_id;

            // El depósito conceptual depende del estado:
            // - En uso → deposito_origen_id
            // - Resto   → deposito_id
            $depositoConceptual = (int) $componente->estado_id === self::ESTADO_EN_USO
                ? $componente->deposito_origen_id
                : $componente->deposito_id;

            // Buscamos todas las filas que forman el mismo grupo visual.
            $filasGrupo = ComponenteModel::where('tipo_id', $tipoId)
                ->whereRaw('LOWER(TRIM(nombre)) = ?', [
                    mb_strtolower(trim((string) $nombre)),
                ])
                ->where(function ($query) use ($depositoConceptual) {
                    // Disponible, Roto y Sin Stock utilizan deposito_id.
                    $query->where(function ($q) use ($depositoConceptual) {
                        $q->whereIn('estado_id', [
                            self::ESTADO_DISPONIBLE,
                            self::ESTADO_ROTO,
                            self::ESTADO_SIN_STOCK,
                        ]);

                        if ($depositoConceptual === null) {
                            $q->whereNull('deposito_id');
                        } else {
                            $q->where('deposito_id', $depositoConceptual);
                        }
                    });

                    // En uso utiliza deposito_origen_id.
                    $query->orWhere(function ($q) use ($depositoConceptual) {
                        $q->where('estado_id', self::ESTADO_EN_USO);

                        if ($depositoConceptual === null) {
                            $q->whereNull('deposito_origen_id');
                        } else {
                            $q->where('deposito_origen_id', $depositoConceptual);
                        }
                    });
                })
                ->lockForUpdate()
                ->get();

            if ($filasGrupo->isEmpty()) {
                throw ValidationException::withMessages([
                    'deleteId' => 'No se encontraron las filas del grupo seleccionado.',
                ]);
            }

            // Eliminamos primero los vínculos con PCs de las filas En uso.
            //
            // No devolvemos las unidades a Disponible porque la operación
            // solicitada es eliminar el componente completo del inventario.
            foreach ($filasGrupo as $fila) {
                if ((int) $fila->estado_id === self::ESTADO_EN_USO) {
                    ComponentePcModel::where('componente_id', $fila->id)->delete();
                }
            }

            // Eliminamos todas las filas que forman el grupo visual.
            foreach ($filasGrupo as $fila) {
                $fila->delete();
            }

            // Registramos una única entrada de historial por la eliminación
            // completa del grupo.
            $historia          = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = 'Eliminó el componente: ' . $nombre . '.';
            $historia->motivo  = $request->input('removeMotivo');
            $historia->tipo_id = 4;
            $historia->save();
        });

        return redirect()->back()->with(
            'success',
            'Componente eliminado correctamente.'
        );
    }


    // ─────────────────────────────────────────────────────────────────────
    // transfer — transferir entre depósitos
    //
    // Esta operación representa un movimiento físico de unidades disponibles:
    //
    //   Disponible / Depósito A
    //          ↓
    //   Disponible / Depósito B
    //
    // No se utiliza para componentes En uso, Rotos o Sin Stock.
    //
    // Si la fila de origen queda en stock = 0, pasa a Sin Stock.
    // Si en el depósito destino ya existe una fila Disponible o Sin Stock
    // del mismo componente, se reutiliza esa fila.
    // ─────────────────────────────────────────────────────────────────────
    public function transfer(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'transferNombre'   => 'required|integer|exists:componente,id',
            'transferStock'    => 'required|integer|min:1',
            'transferDeposito' => 'required|integer|exists:deposito,id',
            'transferMotivo'   => 'nullable|string|max:1000',
        ]);

        $id              = (int) $request->input('transferNombre');
        $stockToTransfer = (int) $request->input('transferStock');
        $depositoDestino = (int) $request->input('transferDeposito');
        $motivo          = $request->input('transferMotivo');

        DB::transaction(function () use (
            $id,
            $stockToTransfer,
            $depositoDestino,
            $motivo,
            $user
        ) {
            // Bloqueamos la fila seleccionada para evitar modificaciones
            // concurrentes mientras se realiza la transferencia.
            $componente = ComponenteModel::whereKey($id)
                ->lockForUpdate()
                ->first();

            if (! $componente) {
                throw ValidationException::withMessages([
                    'transferNombre' => 'El componente seleccionado no existe.',
                ]);
            }

            $estadoOrigen = (int) $componente->estado_id;

            // La transferencia entre depósitos solamente puede hacerse
            // sobre componentes Disponibles o Rotos.
            //
            // En uso tiene su propia lógica de PC y Sin Stock no representa
            // unidades físicas que puedan trasladarse.
            if (! in_array($estadoOrigen, [
                self::ESTADO_DISPONIBLE,
                self::ESTADO_ROTO,
            ], true)) {
                throw ValidationException::withMessages([
                    'transferNombre' => 'Solo se pueden transferir componentes Disponibles o Rotos entre depósitos.',
                ]);
            }

            // Permitir asignar al depósito destino componentes Disponibles
            // o Rotos que hayan quedado sin ubicación física.
            $esSinDeposito = in_array($estadoOrigen, [
                self::ESTADO_DISPONIBLE,
                self::ESTADO_ROTO,
            ], true) && $componente->deposito_id === null;

            if ($componente->deposito_id === null && ! $esSinDeposito) {
                throw ValidationException::withMessages([
                    'transferNombre' => 'El componente seleccionado no tiene un depósito físico asignado.',
                ]);
            }

            $depositoOrigen = $componente->deposito_id !== null
                ? (int) $componente->deposito_id
                : null;

            if ($depositoOrigen !== null && $depositoOrigen === $depositoDestino) {
                throw ValidationException::withMessages([
                    'transferDeposito' => 'El depósito de destino debe ser diferente al depósito de origen.',
                ]);
            }

            // Nunca permitir que el stock quede negativo.
            if ((int) $componente->stock < $stockToTransfer) {
                throw ValidationException::withMessages([
                    'transferStock' => 'La cantidad a transferir supera el stock disponible.',
                ]);
            }

            $depositoOrigenModel = $depositoOrigen !== null
                ? DepositoModel::find($depositoOrigen)
                : null;
            $depositoDestinoModel = DepositoModel::find($depositoDestino);

            if (($depositoOrigen !== null && ! $depositoOrigenModel) || ! $depositoDestinoModel) {
                throw ValidationException::withMessages([
                    'transferDeposito' => 'El depósito de origen o destino no existe.',
                ]);
            }

            $nombreComponente = $componente->nombre;
            $tipoComponente   = $componente->tipo_id;

            // ─────────────────────────────────────────────────────────────
            // 1. Descontar stock del depósito de origen.
            // ─────────────────────────────────────────────────────────────
            $componente->stock -= $stockToTransfer;

            // Si se transfirieron todas las unidades Disponibles, la fila
            // queda como Sin Stock.
            //
            // Una fila Rota no pasa a Sin Stock: simplemente queda con
            // menos unidades Rotos.
            if (
                $componente->stock === 0
                && $estadoOrigen === self::ESTADO_DISPONIBLE
            ) {
                $componente->estado_id = self::ESTADO_SIN_STOCK;
            }

            // Si el componente no tenía depósito y se transfirieron
            // todas sus unidades, eliminar la fila de origen.
            if ($esSinDeposito && (int) $componente->stock === 0) {
                $componente->delete();
            } else {
                $componente->save();
            }

            // ─────────────────────────────────────────────────────────────
            // 2. Buscar la fila equivalente en el depósito destino.
            //
            // La transferencia NO cambia el estado.
            //
            // Disponible → Disponible
            // Roto       → Roto
            // ─────────────────────────────────────────────────────────────

            if ($estadoOrigen === self::ESTADO_DISPONIBLE) {
                // Para Disponible, una fila Sin Stock del mismo componente
                // también puede reutilizarse.
                $componenteDestino = ComponenteModel::where('nombre', $nombreComponente)
                    ->where('tipo_id', $tipoComponente)
                    ->where('deposito_id', $depositoDestino)
                    ->whereIn('estado_id', [
                        self::ESTADO_DISPONIBLE,
                        self::ESTADO_SIN_STOCK,
                    ])
                    ->lockForUpdate()
                    ->first();
            } else {
                // Para Roto solamente buscamos otra fila Rota.
                //
                // Esto evita mezclar un componente Roto con uno Disponible.
                $componenteDestino = ComponenteModel::where('nombre', $nombreComponente)
                    ->where('tipo_id', $tipoComponente)
                    ->where('deposito_id', $depositoDestino)
                    ->where('estado_id', self::ESTADO_ROTO)
                    ->lockForUpdate()
                    ->first();
            }

            if ($componenteDestino) {
                // Ya existe el mismo componente + depósito + estado:
                // acumulamos las unidades transferidas.
                $componenteDestino->stock += $stockToTransfer;

                // Si reutilizamos una fila Sin Stock, vuelve a Disponible.
                if (
                    $estadoOrigen === self::ESTADO_DISPONIBLE
                    && (int) $componenteDestino->estado_id === self::ESTADO_SIN_STOCK
                ) {
                    $componenteDestino->estado_id = self::ESTADO_DISPONIBLE;
                }

                $componenteDestino->save();
            } else {
                // No existe una fila equivalente en el depósito destino.
                // Creamos una nueva manteniendo exactamente el estado
                // que tenía el componente transferido.
                $componenteDestino = new ComponenteModel();
                $componenteDestino->nombre             = $nombreComponente;
                $componenteDestino->tipo_id            = $tipoComponente;
                $componenteDestino->deposito_id        = $depositoDestino;
                $componenteDestino->deposito_origen_id = null;
                $componenteDestino->estado_id          = $estadoOrigen;
                $componenteDestino->stock              = $stockToTransfer;
                $componenteDestino->save();
            }

            // ─────────────────────────────────────────────────────────────
            // 3. Registrar historial.
            // ─────────────────────────────────────────────────────────────
            $estadoNombre = $estadoOrigen === self::ESTADO_ROTO
                ? 'Roto'
                : 'Disponible';

            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $origenNombre = $depositoOrigenModel?->nombre ?? 'Sin depósito';

            $historia->detalle = ($depositoOrigen === null ? 'Asignó ' : 'Transfirió ')
                . $stockToTransfer . ' '
                . $nombreComponente
                . ' (' . $estadoNombre . ')'
                . ' del depósito: ' . $origenNombre
                . ' al depósito: ' . $depositoDestinoModel->nombre . '.';
            $historia->motivo  = $motivo;
            $historia->tipo_id = 4;
            $historia->save();
        });

        return redirect()->back()->with(
            'success',
            'Componente transferido correctamente.'
        );
    }


    // ─────────────────────────────────────────────────────────────────────
    // transferState — "Cambiar estado" desde gest_componentes
    //
    // Transiciones permitidas:
    //   Disponible (4) → Roto (2)
    //   Roto (2)       → Disponible (4)
    //
    // Todo lo demás (En uso, Sin Stock como destino manual, etc.) se rechaza
    // porque requiere lógica de PC o no tiene sentido como operación directa.
    //
    // Las cantidades pueden ser parciales: si solo se rompen 2 de 10, se
    // descuentan 2 de la fila Disponible y se agregan a la fila Roto.
    //
    // Gestión de estado 7 (Sin Stock):
    //   - Si la fila origen queda en stock = 0 tras el descuento, pasa a
    //     Sin Stock (7). Esto ocurre solo cuando el origen era Disponible (4).
    //   - Si la fila destino existe pero estaba en Sin Stock (7) y el destino
    //     es Disponible (4), se reactiva a Disponible al recibir el stock.
    // ─────────────────────────────────────────────────────────────────────
    public function transferState(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'transferStateNombre' => 'required|integer|exists:componente,id',
            'transferStateEstado' => 'required|integer',
            'transferStateStock'  => 'required|integer|min:1',
            'transferStateMotivo' => 'required|string|max:1000',
        ]);

        $id              = (int) $request->input('transferStateNombre');
        $stockToTransfer = (int) $request->input('transferStateStock');
        $estadoDestino   = (int) $request->input('transferStateEstado');
        $motivo          = $request->input('transferStateMotivo');

        DB::transaction(function () use (
            $id,
            $stockToTransfer,
            $estadoDestino,
            $motivo,
            $user
        ) {
            $componente = ComponenteModel::whereKey($id)
                ->lockForUpdate()
                ->first();

            if (! $componente) {
                throw ValidationException::withMessages([
                    'transferStateNombre' => 'El componente seleccionado no existe.',
                ]);
            }

            $estadoOrigen = (int) $componente->estado_id;
            $permitidas = self::TRANSICIONES_PERMITIDAS[$estadoOrigen] ?? [];

            if (! in_array($estadoDestino, $permitidas, true)) {
                $nombreOrigen  = $componente->estado->nombre ?? $estadoOrigen;
                $nombreDestino = EstadoComponenteModel::find($estadoDestino)->nombre ?? $estadoDestino;

                throw ValidationException::withMessages([
                    'transferStateEstado' => "No se permite cambiar de \"{$nombreOrigen}\" a \"{$nombreDestino}\" desde esta operación.",
                ]);
            }

            if ((int) $componente->stock < $stockToTransfer) {
                throw ValidationException::withMessages([
                    'transferStateStock' => 'Stock insuficiente en el estado de origen.',
                ]);
            }

            $nombreOrigen = $componente->estado->nombre;

            $componente->stock -= $stockToTransfer;
            if ($componente->stock === 0 && $estadoOrigen === self::ESTADO_DISPONIBLE) {
                $componente->estado_id = self::ESTADO_SIN_STOCK;
            }
            $componente->save();

            if ($estadoDestino === self::ESTADO_DISPONIBLE) {
                $componenteDestino = ComponenteModel::where('nombre', $componente->nombre)
                    ->where('tipo_id', $componente->tipo_id)
                    ->where('deposito_id', $componente->deposito_id)
                    ->whereIn('estado_id', [self::ESTADO_DISPONIBLE, self::ESTADO_SIN_STOCK])
                    ->lockForUpdate()
                    ->first();
            } else {
                $componenteDestino = ComponenteModel::where('nombre', $componente->nombre)
                    ->where('tipo_id', $componente->tipo_id)
                    ->where('estado_id', $estadoDestino)
                    ->where('deposito_id', $componente->deposito_id)
                    ->lockForUpdate()
                    ->first();
            }

            if ($componenteDestino) {
                $componenteDestino->stock += $stockToTransfer;
                if (
                    $estadoDestino === self::ESTADO_DISPONIBLE
                    && $componenteDestino->estado_id === self::ESTADO_SIN_STOCK
                ) {
                    $componenteDestino->estado_id = self::ESTADO_DISPONIBLE;
                }
                $componenteDestino->save();
            } else {
                $nueva              = new ComponenteModel();
                $nueva->nombre      = $componente->nombre;
                $nueva->tipo_id     = $componente->tipo_id;
                $nueva->estado_id   = $estadoDestino;
                $nueva->deposito_id = $componente->deposito_id;
                $nueva->stock       = $stockToTransfer;
                $nueva->save();
            }

            $nombreDestino = EstadoComponenteModel::find($estadoDestino)->nombre ?? $estadoDestino;

            $historia          = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = 'Cambió ' . $stockToTransfer . ' ' . $componente->nombre
                . '/s del estado: ' . $nombreOrigen
                . ' al estado: ' . $nombreDestino . '.';
            $historia->motivo  = $motivo;
            $historia->tipo_id = 4;
            $historia->save();
        });

        return redirect()->back()->with('success', 'Estado del componente cambiado correctamente.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // retirarComponenteRoto — operación "En uso → Roto" desde una PC
    //
    // Uso: cuando un componente instalado en una PC se rompe. Evita obligar
    // al técnico a hacer el proceso en 3 pasos (devolver → buscar → cambiar
    // a Roto). Esta operación hace todo en una transacción:
    //
    //   1. Busca el vínculo componente_pc y lo elimina.
    //   2. Descuenta 1 unidad de la fila "En uso" del componente.
    //   3. Crea o incrementa la fila "Roto" en el depósito de origen
    //      (deposito_origen_id). Si deposito_origen_id es NULL, la fila
    //      Roto queda con deposito_id = NULL (depósito no asignado).
    //   4. Registra en historia.
    //
    // Parámetros:
    //   $componente_id  — ID de la fila de componente con estado_id = 5
    //   $pc_id          — ID de la PC a la que estaba asignado
    //   $pc_identificador / $pc_nombre — para el mensaje de historia
    //   $motivo         — motivo del retiro
    //
    // Retorna: true en éxito, false si el vínculo o la fila En uso no existe.
    // ─────────────────────────────────────────────────────────────────────
    public function retirarComponenteRotoFromRequest(Request $request)
    {
        $data = $request->validate([
            'componente_id'    => 'required|integer|exists:componente,id',
            'pc_id'            => 'required|integer|exists:pc,id',
            'pc_identificador' => 'required|string|max:255',
            'pc_nombre'        => 'required|string|max:255',
            'motivo'           => 'required|string|max:1000',
        ]);

        $this->retirarComponenteRoto(
            (int) $data['componente_id'],
            (int) $data['pc_id'],
            $data['pc_identificador'],
            $data['pc_nombre'],
            $data['motivo']
        );

        return redirect()->back()->with('success', 'El componente fue retirado como roto correctamente.');
    }

    public function retirarComponenteRoto(
        int $componente_id,
        int $pc_id,
        string $pc_identificador,
        string $pc_nombre,
        string $motivo
    ): bool {
        $user = Auth::user();

        return DB::transaction(function () use (
            $componente_id,
            $pc_id,
            $pc_identificador,
            $pc_nombre,
            $motivo,
            $user
        ) {
            // Bloqueamos el vínculo y la fila de componente dentro de la
            // misma transacción para evitar que dos operaciones concurrentes
            // retiren la misma unidad.
            $vinculo = ComponentePcModel::where('pc_id', $pc_id)
                ->where('componente_id', $componente_id)
                ->lockForUpdate()
                ->first();

            if (! $vinculo) {
                throw ValidationException::withMessages([
                    'componente' => 'El componente no está asignado a la PC indicada.',
                ]);
            }

            $componente = ComponenteModel::whereKey($componente_id)
                ->lockForUpdate()
                ->first();

            if (! $componente) {
                throw ValidationException::withMessages([
                    'componente' => 'El componente seleccionado no existe.',
                ]);
            }

            if ((int) $componente->estado_id !== self::ESTADO_EN_USO) {
                throw ValidationException::withMessages([
                    'componente' => 'Solo se puede retirar como roto un componente En uso.',
                ]);
            }

            if ((int) $componente->stock < 1) {
                throw ValidationException::withMessages([
                    'componente' => 'La fila En uso no tiene unidades disponibles para retirar.',
                ]);
            }

            $nombre             = $componente->nombre;
            $tipoId             = $componente->tipo_id;
            $depositoOrigenId  = $componente->deposito_origen_id;

            // 1 — Retirar la relación con la PC.
            $vinculo->delete();

            // 2 — Descontar la unidad de En uso.
            $componente->stock -= 1;

            $otrosVinculos = ComponentePcModel::where('componente_id', $componente->id)->count();

            if ($componente->stock === 0 && $otrosVinculos === 0) {
                $componente->delete();
            } else {
                $componente->save();
            }

            // 3 — Crear/incrementar la fila Roto en el depósito de origen.
            // Si el origen era desconocido, queda NULL: no inventamos un depósito.
            $filaRoto = ComponenteModel::where('nombre', $nombre)
                ->where('tipo_id', $tipoId)
                ->where('estado_id', self::ESTADO_ROTO)
                ->when(
                    $depositoOrigenId === null,
                    fn($query) => $query->whereNull('deposito_id'),
                    fn($query) => $query->where('deposito_id', $depositoOrigenId)
                )
                ->lockForUpdate()
                ->first();

            if ($filaRoto) {
                $filaRoto->stock += 1;
                $filaRoto->save();
            } else {
                $filaRoto                     = new ComponenteModel();
                $filaRoto->nombre             = $nombre;
                $filaRoto->tipo_id            = $tipoId;
                $filaRoto->estado_id          = self::ESTADO_ROTO;
                $filaRoto->deposito_id        = $depositoOrigenId;
                $filaRoto->deposito_origen_id = null;
                $filaRoto->stock              = 1;
                $filaRoto->save();
            }

            // 4 — Registrar la operación en historial.
            $historia                  = new HistoriaModel();
            $historia->tecnico         = $user->name;
            $historia->detalle         = 'Retiró como roto 1 ' . $nombre
                . ' de la PC: ' . $pc_identificador . ' - ' . $pc_nombre . '.';
            $historia->motivo          = $motivo;
            $historia->tipo_id         = 4;
            $historia->save();

            return true;
        });
    }

    // ─────────────────────────────────────────────────────────────────────
    // consumirComponenteEnUso — consume una unidad que estaba instalada en
    // un dispositivo consumible (por ejemplo, un tóner). No la devuelve a
    // Disponible ni crea un estado artificial: la unidad deja de formar parte
    // del inventario cuando se consume.
    // ─────────────────────────────────────────────────────────────────────
    public function consumirComponenteEnUso($id_attr, $cantidad = 1)
    {
        $cantidad = (int) $cantidad;

        if (! $id_attr) {
            throw ValidationException::withMessages([
                'componente' => 'El componente a consumir es obligatorio.',
            ]);
        }

        if ($cantidad < 1) {
            throw ValidationException::withMessages([
                'componente' => 'La cantidad a consumir debe ser mayor que cero.',
            ]);
        }

        return DB::transaction(function () use ($id_attr, $cantidad) {
            $componente = ComponenteModel::whereKey($id_attr)
                ->lockForUpdate()
                ->first();

            if (! $componente) {
                throw ValidationException::withMessages([
                    'componente' => 'El componente seleccionado no existe.',
                ]);
            }

            if ((int) $componente->estado_id !== self::ESTADO_EN_USO) {
                throw ValidationException::withMessages([
                    'componente' => 'Solo se puede consumir un componente que esté En uso.',
                ]);
            }

            if ((int) $componente->stock < $cantidad) {
                throw ValidationException::withMessages([
                    'componente' => 'La cantidad a consumir supera las unidades En uso.',
                ]);
            }

            $componente->stock -= $cantidad;

            if ($componente->stock === 0) {
                $componente->delete();
            } else {
                $componente->save();
            }

            return true;
        });
    }

    // ─────────────────────────────────────────────────────────────────────
    // transferStateByPc — asignación / devolución a través de PcController
    // (sin cambios respecto al original salvo uso de constantes)
    // ─────────────────────────────────────────────────────────────────────
    public function transferStateByPc(
        $id_attr,
        $stockToTransfer_attr,
        $estadoDestino_attr,
        $motivo_attr,
        $pc_iden_attr,
        $pc_nombre_attr,
        $need_historia,
        $mantenimiento
    ) {
        if (! $id_attr) {
            return null;
        }

        $cantidad = (int) $stockToTransfer_attr;
        $estadoDestino = (int) $estadoDestino_attr;

        if ($cantidad < 1) {
            throw ValidationException::withMessages([
                'componente' => 'La cantidad a transferir debe ser mayor que cero.',
            ]);
        }

        $user = Auth::user();

        return DB::transaction(function () use (
            $id_attr,
            $cantidad,
            $estadoDestino,
            $motivo_attr,
            $pc_iden_attr,
            $pc_nombre_attr,
            $need_historia,
            $mantenimiento,
            $user
        ) {
            $componente = ComponenteModel::whereKey($id_attr)
                ->lockForUpdate()
                ->first();

            if (! $componente) {
                throw ValidationException::withMessages([
                    'componente' => 'El componente seleccionado no existe.',
                ]);
            }

            // Las operaciones de PC solo admiten Disponible ↔ En uso.
            // No se permite pasar un componente a otro estado mediante este método.
            if (! in_array($estadoDestino, [self::ESTADO_DISPONIBLE, self::ESTADO_EN_USO], true)) {
                throw ValidationException::withMessages([
                    'componente' => 'La operación de PC solo admite los estados Disponible y En uso.',
                ]);
            }

            if ($estadoDestino === self::ESTADO_EN_USO) {
                if ((int) $componente->estado_id !== self::ESTADO_DISPONIBLE) {
                    throw ValidationException::withMessages([
                        'componente' => 'Solo se puede asignar a una PC un componente Disponible.',
                    ]);
                }
            } elseif ($estadoDestino === self::ESTADO_DISPONIBLE) {
                if ((int) $componente->estado_id !== self::ESTADO_EN_USO) {
                    throw ValidationException::withMessages([
                        'componente' => 'Solo se puede devolver a Disponible un componente En uso.',
                    ]);
                }
            }

            if ((int) $componente->stock < $cantidad) {
                throw ValidationException::withMessages([
                    'componente' => 'La cantidad solicitada supera el stock de la fila seleccionada.',
                ]);
            }

            // ── Disponible → En uso ──────────────────────────────────────────
            if ($estadoDestino === self::ESTADO_EN_USO) {
                $depositoOrigen = $componente->deposito_id;

                $componente->stock -= $cantidad;
                if ($componente->stock === 0) {
                    $componente->estado_id = self::ESTADO_SIN_STOCK;
                }
                $componente->save();

                // Una fila En uso representa unidades con el mismo origen.
                // Nunca mezclamos dos depósitos de origen en una misma fila.
                $componenteDestino = ComponenteModel::where('nombre', $componente->nombre)
                    ->where('tipo_id', $componente->tipo_id)
                    ->where('estado_id', self::ESTADO_EN_USO)
                    ->where(function ($query) use ($depositoOrigen) {
                        if ($depositoOrigen === null) {
                            $query->whereNull('deposito_origen_id');
                        } else {
                            $query->where('deposito_origen_id', $depositoOrigen);
                        }
                    })
                    ->lockForUpdate()
                    ->first();

                if ($componenteDestino) {
                    $componenteDestino->stock += $cantidad;
                    $componenteResultado = $componenteDestino;
                } else {
                    $componenteResultado = new ComponenteModel();
                    $componenteResultado->nombre             = $componente->nombre;
                    $componenteResultado->tipo_id            = $componente->tipo_id;
                    $componenteResultado->estado_id          = self::ESTADO_EN_USO;
                    $componenteResultado->deposito_id        = null;
                    $componenteResultado->deposito_origen_id = $depositoOrigen;
                    $componenteResultado->stock               = $cantidad;
                }

                $componenteResultado->save();
                // ── En uso → Disponible ──────────────────────────────────────────
            } elseif ($estadoDestino === self::ESTADO_DISPONIBLE) {
                $depositoDestino = $componente->deposito_origen_id;

                $componente->stock -= $cantidad;

                // Una fila En uso no se convierte en Sin Stock: el estado 7
                // representa únicamente filas disponibles con stock cero. Si ya
                // no quedan unidades En uso, la fila se elimina si tampoco tiene
                // vínculos pendientes. Esto se hace después de que el llamador haya
                // retirado el vínculo componente_pc; si todavía existe uno, la fila
                // se conserva con stock > 0 en los casos normales.
                $vinculosRestantes = ComponentePcModel::where('componente_id', $componente->id)->count();
                if ($componente->stock === 0 && $vinculosRestantes === 0) {
                    $componente->delete();
                } else {
                    $componente->save();
                }

                // Si el origen era desconocido, se conserva NULL. No se busca
                // una fila de otro depósito porque eso inventaría una ubicación.
                $componenteDestino = ComponenteModel::where('nombre', $componente->nombre)
                    ->where('tipo_id', $componente->tipo_id)
                    ->where('estado_id', self::ESTADO_DISPONIBLE)
                    ->where(function ($query) use ($depositoDestino) {
                        if ($depositoDestino === null) {
                            $query->whereNull('deposito_id');
                        } else {
                            $query->where('deposito_id', $depositoDestino);
                        }
                    })
                    ->lockForUpdate()
                    ->first();

                if ($componenteDestino) {
                    $componenteDestino->stock += $cantidad;
                    $componenteResultado = $componenteDestino;
                } else {
                    $componenteResultado = new ComponenteModel();
                    $componenteResultado->nombre             = $componente->nombre;
                    $componenteResultado->tipo_id            = $componente->tipo_id;
                    $componenteResultado->estado_id          = self::ESTADO_DISPONIBLE;
                    $componenteResultado->deposito_id        = $depositoDestino;
                    $componenteResultado->deposito_origen_id = null;
                    $componenteResultado->stock               = $cantidad;
                }

                $componenteResultado->save();
            }

            if ($need_historia) {
                $historia          = new HistoriaModel();
                $historia->tecnico = $user->name;
                $historia->motivo  = $motivo_attr;
                $historia->tipo_id = 4;
                $historia->detalle = $mantenimiento
                    ? 'Usó ' . $cantidad . ' ' . $componente->nombre
                    . '/s en el mantenimiento de la PC: ' . $pc_iden_attr . ' - ' . $pc_nombre_attr . '.'
                    : 'Usó ' . $cantidad . ' ' . $componente->nombre
                    . '/s para el armado de la PC: ' . $pc_iden_attr . ' - ' . $pc_nombre_attr . '.';
                $historia->save();
            }

            return $componenteResultado->id;
        });
    }
}
