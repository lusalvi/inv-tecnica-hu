<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComponenteModel;
use App\Models\TipoComponenteModel;
use App\Models\DepositoModel;
use App\Models\HistoriaModel;
use App\Models\HDD;
use App\Models\SDD;
use App\Models\EstadoComponenteModel;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;

class ComponenteController extends Controller
{


    public function index()
    {
        $componentes = ComponenteModel::with(['tipo', 'deposito', 'estado'])->get();
        $historias = HistoriaModel::where('tipo_id', 4)
            ->orderBy('created_at', 'desc')
            ->get();
        $tipos = TipoComponenteModel::all();
        $depositos = DepositoModel::all();
        $nombresDepositos = DepositoModel::pluck('nombre');
        $estados = EstadoComponenteModel::all();


        // Pasar los datos a la vista
        return view('gest_componentes', ['estados' => $estados, 'componentes' => $componentes, 'historias' => $historias, 'tipos' => $tipos, 'depositos' => $depositos, 'nombresDepositos' => $nombresDepositos]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'addNombre' => 'required|string|max:255|unique:componente,nombre',
            'addTipo' => 'required|integer',
            'addDeposito' => 'required|integer',
        ]);

        // Crear un nuevo registro
        $componente = new ComponenteModel();
        $componente->nombre = $request->input('addNombre');
        $componente->tipo_id = $request->input('addTipo');
        $componente->deposito_id = $request->input('addDeposito');
        //$componente->stock = $request->input('addStock');
        $componente->save();

        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Creó el componente: " . $request->input('addNombre') . ".";
        $historia->motivo = "Creación de componente.";
        $historia->tipo_id = 4;
        $historia->save();

        return redirect()->back()->with('success', 'Componente guardado correctamente.');
    }
    public function edit(Request $request)
    {
        $user = Auth::user();

        $id = $request->input('editId');
        $componente = ComponenteModel::find($id);

        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->tipo_id = 4;
        $historia->motivo = $request->input('editMotivo');
        if ($componente->nombre != $request->input('editNombre') && $componente->tipo_id != $request->input('editTipo')) {
            $historia->detalle = "Editó el nombre del componente: " . $componente->nombre . " a: " . $request->input('editNombre') . ", y la categoría de: " . ($componente->tipo->nombre ?? 'no asignado') . " a " . TipoComponenteModel::find($request->input('editTipo'))->nombre . ".";
        } elseif ($componente->nombre != $request->input('editNombre')) {
            $historia->detalle = "Editó el nombre del componente: " . $componente->nombre . " a: " . $request->input('editNombre') . ".";
        } elseif ($componente->tipo_id != $request->input('editTipo')) {
            $historia->detalle = "Edito la categoría del componente: " . $componente->nombre . " de " . ($componente->tipo->nombre ?? 'no asignado') . " a " . TipoComponenteModel::find($request->input('editTipo'))->nombre . ".";
        }

        $componente->tipo_id = $request->input('editTipo');
        $componente->nombre = $request->input('editNombre');

        $historia->save();
        $componente->update();

        return redirect()->back()->with('success', 'Componente editado correctamente.');
    }

    public function add_stock(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'editAddStock' => 'required',
        ]);

        $id = $request->input('editNombreStock');
        $componente = ComponenteModel::find($id);

        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Agregó " . $request->input('editAddStock') . " componente/s al stock de: " . $componente->nombre . "/s.";
        $historia->motivo = $request->input('editAddStockMotivo');
        $historia->tipo_id = 4;
        $historia->save();

        $componente->stock = ($componente->stock + $request->input('editAddStock'));

        if ($componente->stock >= 1 && $componente->estado_id = 7) {
            $componente->estado_id = 4;
        }
        $componente->update();



        return redirect()->back()->with('success', 'Stock agregado correctamente.');
    }
    public function remove_stock(Request $request)
    {
        $user = Auth::user();

        $id = $request->input('removeNombreStock');
        $componente = ComponenteModel::find($id);

        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Eliminó " . $request->input('removeStock') . " componente/s del stock de: " . $componente->nombre . "/s.";
        $historia->motivo = $request->input('removeStockMotivo');
        $historia->tipo_id = 4;
        $historia->save();

        $componente->stock = ($componente->stock - $request->input('removeStock'));

        if ($componente->stock == 0 && $componente->estado_id = 4) {
            $componente->estado_id = 7;
        }
        $componente->update();

        return redirect()->back()->with('success', 'Stock eliminado correctamente.');
    }
    public function delete(Request $request)
    {
        $user = Auth::user();
        // Crear un nuevo registro
        $id = $request->input('deleteId');
        $componente = ComponenteModel::find($id);

        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Eliminó el componente: " . $componente->nombre . ".";
        $historia->motivo = $request->input('removeMotivo');
        $historia->tipo_id = 4;
        $historia->save();

        $componente->delete();

        return redirect()->back()->with('success', 'Componente eliminado correctamente.');
    }

    public function transfer(Request $request)
    {
        $user = Auth::user();

        // Obtener el ID del componente y otros datos del formulario
        $id = $request->input('transferNombre');
        $stockToTransfer = $request->input('transferStock');
        $depositoDestino = $request->input('transferDeposito');

        $motivo = $request->input('transferMotivo');

        // Encontrar el componente actual
        $componente = ComponenteModel::find($id);

        $depositoActual = $componente->deposito->nombre;

        // Reducir el stock del componente original
        if ($componente->stock >= $stockToTransfer) {
            $componente->stock -= $stockToTransfer;
            $componente->save();
        } else {
            return redirect()->back()->with('error', 'Stock insuficiente en el depósito de origen.');
        }

        // Verificar si el depósito destino existe
        $depositoExiste = DepositoModel::find($componente->deposito_id);

        if ($componente->deposito_id == null || $depositoExiste == null) {
            // Si el depósito no está asignado o no existe, modificar el deposito_id del componente actual
            $componente->deposito_id = $depositoDestino;
            $componente->stock += $stockToTransfer; // Revertir la reducción de stock
            $componente->save();
        } else {
            // Buscar el componente en el depósito destino
            $componenteDestino = ComponenteModel::where('nombre', $componente->nombre)
                ->where('deposito_id', $depositoDestino)
                ->where('estado_id', $componente->estado_id)
                ->first();

            if ($componenteDestino) {
                // Si el componente ya existe en el depósito destino, actualizar su stock
                $componenteDestino->stock += $stockToTransfer;
                $componenteDestino->save();
            } else {
                // Si el componente no existe en el depósito destino, crear uno nuevo
                $newComponente = new ComponenteModel();
                $newComponente->nombre = $componente->nombre;
                $newComponente->tipo_id = $componente->tipo_id;
                $newComponente->deposito_id = $depositoDestino;
                $newComponente->estado_id = $componente->estado_id;
                $newComponente->stock = $stockToTransfer;
                $newComponente->save();
            }
        }

        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Transfirió " . $stockToTransfer . " " . $componente->nombre . "/s del depósito: " . $depositoActual . " al depósito: " . DepositoModel::find($depositoDestino)->nombre . ".";
        $historia->motivo = $motivo;
        $historia->tipo_id = 4; // Tipo de transferencia
        $historia->save();

        return redirect()->back()->with('success', 'Componente transferido correctamente.');
    }

    public function transferState(Request $request)
    {
        $user = Auth::user();

        // Obtener el ID del componente y otros datos del formulario
        $id = $request->input('transferStateNombre');
        $stockToTransfer = $request->input('transferStateStock');
        $estadoDestino = $request->input('transferStateEstado');
        $motivo = $request->input('transferStateMotivo');

        // Encontrar el componente actual
        $componente = ComponenteModel::find($id);

        // Obtener el estado actual del componente
        log::info($componente->estado_id);
        $estadoActual = EstadoComponenteModel::find($componente->estado_id);

        // Reducir el stock del componente original
        if ($componente->stock >= $stockToTransfer) {
            $componente->stock -= $stockToTransfer;
            $componente->save();
        } else {
            return redirect()->back()->with('error', 'Stock insuficiente en el estado de origen.');
        }

        // Verificar si el estado destino existe
        $estadoExiste = EstadoComponenteModel::find($estadoDestino);

        if ($componente->estado_id == null || $estadoExiste == null) {
            // Si el estado no está asignado o no existe, modificar el estado_id del componente actual
            $componente->estado_id = $estadoDestino;
            $componente->stock += $stockToTransfer; // Revertir la reducción de stock
            $componente->save();
        } else {
            // Buscar un componente en el estado destino
            $componenteDestino = ComponenteModel::where('nombre', $componente->nombre)
                ->where('estado_id', $estadoDestino)
                ->where('deposito_id', $componente->deposito_id)
                ->first();

            if ($componenteDestino) {
                // Si el componente ya existe en el estado destino, actualizar su stock
                $componenteDestino->stock += $stockToTransfer;
                $componenteDestino->save();
            } else {
                // Si el componente no existe en el estado destino, crear uno nuevo
                $newComponente = new ComponenteModel();
                $newComponente->nombre = $componente->nombre;
                $newComponente->tipo_id = $componente->tipo_id;
                $newComponente->estado_id = $estadoDestino;
                $newComponente->deposito_id = $componente->deposito_id;
                $newComponente->stock = $stockToTransfer;
                $newComponente->save();
            }
        }

        // Registrar la transferencia en la historia
        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Cambió " . $stockToTransfer . " " . $componente->nombre . "/s del estado: " . $estadoActual->nombre . " al estado: " . ($estadoExiste->nombre ?? 'Estado no asignado') . ".";
        $historia->motivo = $motivo;
        $historia->tipo_id = 4; // Tipo de transferencia
        $historia->save();

        return redirect()->back()->with('success', 'Estado del componente cambiado correctamente.');
    }

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
        if ($id_attr) {
            $user = Auth::user();

            // Obtener el ID del componente y otros datos del formulario
            $id = $id_attr;
            $stockToTransfer = $stockToTransfer_attr;
            $estadoDestino = $estadoDestino_attr;
            $motivo = $motivo_attr;

            // Encontrar el componente actual
            $componente = ComponenteModel::find($id);

            // Reducir el stock del componente original
            if ($componente->stock >= $stockToTransfer) {
                $componente->stock -= $stockToTransfer;
                $componente->save();
            }

            // Verificar si el estado destino existe
            $estadoExiste = EstadoComponenteModel::find($estadoDestino);

            // Variable para almacenar el componente que se devolverá
            $componenteResultado = null;

            if ($componente->estado_id == null || $estadoExiste == null) {
                // Si el estado no está asignado o no existe, modificar el estado_id del componente actual
                $componente->estado_id = $estadoDestino;
                $componente->stock += $stockToTransfer; // Revertir la reducción de stock
                $componente->save();

                $componenteResultado = $componente; // Guardar el componente modificado
            } else {
                // Buscar un componente en el estado destino
                $componenteDestino = ComponenteModel::where('nombre', $componente->nombre)
                    ->where('estado_id', $estadoDestino)
                    ->first();

                if ($componenteDestino) {
                    // Si el componente ya existe en el estado destino, actualizar su stock
                    $componenteDestino->stock += $stockToTransfer;
                    $componenteDestino->save();

                    $componenteResultado = $componenteDestino; // Guardar el componente destino
                } else {
                    // Si el componente no existe en el estado destino, crear uno nuevo
                    if ($estadoDestino == 5) {
                        $newComponente = new ComponenteModel();
                        $newComponente->nombre = $componente->nombre;
                        $newComponente->tipo_id = $componente->tipo_id;
                        $newComponente->estado_id = $estadoDestino;
                        $newComponente->deposito_id = null;
                        $newComponente->stock = $stockToTransfer;
                        $newComponente->save();
                    } else {
                        $newComponente = new ComponenteModel();
                        $newComponente->nombre = $componente->nombre;
                        $newComponente->tipo_id = $componente->tipo_id;
                        $newComponente->estado_id = $estadoDestino;
                        $newComponente->deposito_id = $componente->deposito_id;
                        $newComponente->stock = $stockToTransfer;
                        $newComponente->save();
                    }


                    $componenteResultado = $newComponente; // Guardar el nuevo componente
                }
            }

            // Registrar la transferencia en la historia
            if ($need_historia) {
                if ($mantenimiento) {
                    $historia = new HistoriaModel();
                    $historia->tecnico = $user->name;
                    $historia->detalle = "Usó " . $stockToTransfer . " " . $componente->nombre . "/s " . " en el mantenimiento de la PC: " . $pc_iden_attr . " - " . $pc_nombre_attr . ".";
                    $historia->motivo = $motivo;
                    $historia->tipo_id = 4; // Tipo de transferencia
                    $historia->save();
                } else {
                    $historia = new HistoriaModel();
                    $historia->tecnico = $user->name;
                    $historia->detalle = "Usó " . $stockToTransfer . " " . $componente->nombre . "/s " . " para el armado de la PC: " . $pc_iden_attr . " - " . $pc_nombre_attr . ".";
                    $historia->motivo = $motivo;
                    $historia->tipo_id = 4; // Tipo de transferencia
                    $historia->save();
                }
            }

            return $componenteResultado->id; // Devolver el ID del componente resultante
        }
    }
}
