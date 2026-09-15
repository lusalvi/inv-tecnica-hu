<?php

namespace App\Http\Controllers;

use Carbon\Traits\ToStringFormat;
use Illuminate\Http\Request;
use App\Models\ComponenteModel;
use App\Models\AreaModel;
use App\Models\TipoComponenteModel;
use App\Models\DepositoModel;
use App\Models\HistoriaModel;
use App\Models\PcModel;
use App\Models\ComponentePcModel;
use App\Models\EstadoComponenteModel;
use App\Models\ImpresoraModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ImpresoraController extends Controller
{


    public function index()
    {
        $componentesModel = new ComponenteModel();

        // Obtener componentes por tipo con conexiones de tipo y depósito
        $toners = $componentesModel->getComponenteByTipo('Toner', '');
        $impresoras = ImpresoraModel::with(['area', 'deposito', 'componentes.tipo'])->get();

        // Obtener otras entidades
        $historias = HistoriaModel::where('tipo_id', 7)
            ->orderBy('created_at', 'desc')
            ->get();
        $tipos = TipoComponenteModel::all();
        $depositos = DepositoModel::all();
        $areas = AreaModel::orderBy('nombre', 'asc')
            ->get();

        // Pasar los datos a la vista
        return view('gest_impresoras', [
            'historias' => $historias,
            'depositos' => $depositos,
            'areas' => $areas,
            'toners' => $toners,
            'impresoras' => $impresoras
        ]);
    }


    public function store(Request $request)
    {
        $user = Auth::user();
        $areaModel = new AreaModel();

        // Validación
        $request->validate([
            'addNombre' => 'required|string|max:255|unique:pc,nombre',
            'addIdentificador' => 'required|string|max:255|unique:pc,identificador'
        ]);

        // Crear un nuevo registro
        $transferecia = new ComponenteController;
        $impresora = new ImpresoraModel();
        $impresora->nombre = $request->input('addNombre');
        $impresora->identificador = $request->input('addIdentificador');
        $impresora->ip = $request->input('addIp');
        $impresora->deposito_id = $request->input('addDeposito');
        if (!$request->input('addDeposito')) {
            if ($request->input('addNroConsul')) {
                $area = AreaModel::find($request->input('addArea'))->nombre . " " . $request->input('addNroConsul');
            } else {
                $area = AreaModel::find($request->input('addArea'))->nombre;
            }
            if ($areaModel->findByName($area)) {
                $impresora->area_id = $areaModel->findByName($area)->id;
            } else {
                $areaNueva = new AreaModel();
                $areaNueva->nombre = $area;
                $areaNueva->visible = false;
                $areaNueva->save();

                $impresora->area_id = $areaNueva->id;
            }
        }
        $impresora->toner_id = $transferecia->transferStateByPc($request->input('addToner'), 1, 5, "", null, null, false, false);
        $impresora->marca_modelo = $request->input('addMarca');
        $impresora->save();

        // Guardar historia
        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Cargó la impresora: " . $request->input('addIdentificador') . " - " . $request->input('addNombre') . " - " . $request->input('addMarca') . ".";
        $historia->motivo = "Carga de Impresora";
        $historia->componente_id = $impresora->id;
        $historia->tipo_dispositivo = 'Impresora'; // Tipo de transferencia
        $historia->tipo_id = 7;
        $historia->save();

        return redirect()->back()->with('success', 'Impresora guardada correctamente.');
    }


    public function edit(Request $request)
    {
        $user = Auth::user();
        $areaModel = new AreaModel();


        $id = $request->input('editId');
        $pc = PcModel::find($id);
        $impresora = ImpresoraModel::find($id);



        if ($request->input("editDetalle") != null || $request->input("editDetalle") != "") {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = $request->input("editDetalle");
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora'; // Tipo de transferencia
            $historia->tipo_id = 7; // Ajusta el tipo_id según sea necesario
            $historia->save();
        }

        if ($impresora->toner_id != $request->input('editToner')) {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = "Cambió el toner de la impresora: " . $impresora->identificador . " - " . $impresora->nombre . " de " . (ComponenteModel::find($impresora->toner_id)->nombre ?? "toner no asignado") . " a " . (ComponenteModel::find($request->input('editToner'))->nombre) . ".";
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora'; // Tipo de transferencia
            $historia->tipo_id = 7; // Ajusta el tipo_id según sea necesario
            $historia->save();

            $transferencia = new ComponenteController();
            $transferencia->transferStateByPc($impresora->toner_id, 1, 6, "", null, null, false, false);
            $transferencia->transferStateByPc($request->input('editToner'), 1, 5, "", null, null, false, false);
        }
        $impresora->toner_id = $request->input('editToner');

        // Actualizar información básica de la impresora
        if ($impresora->deposito_id != $request->input('editDeposito') && $request->input('editDeposito') != null) {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = "Cambió el depósito de la impresora: " . $impresora->identificador . " - " . $impresora->nombre . " de " . (DepositoModel::find($impresora->deposito_id)->nombre ?? "depósito no asignado") . " a " . (DepositoModel::find($request->input('editDeposito'))->nombre ?? "depósito no asignado") . (($area = AreaModel::find($impresora->area_id)) ? ', se quitó del área ' . ($area->nombre ?? '') : '') . ".";
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora'; // Tipo de transferencia
            $historia->tipo_id = 7; // Ajusta el tipo_id según sea necesario
            $historia->save();
            $impresora->deposito_id = $request->input('editDeposito');
            $impresora->area_id = null;
        }


        if ($impresora->area_id != $request->input('editArea') && $request->input('editArea') != null) {
            if ($request->input('editArea') == 27) {
                $area = AreaModel::find($request->input('editArea'))->nombre . " " . ($request->input('editNroConsul') ?? '');
            } else {
                $area = AreaModel::find($request->input('editArea'))->nombre;
            }
            if ($areaModel->findByName($area)) {
                $historia = new HistoriaModel();
                $historia->tecnico = $user->name;
                $historia->detalle = "Cambió el área de la impresora: " . $impresora->identificador . " - " . $impresora->nombre . " de " . (AreaModel::find($impresora->area_id)->nombre ?? "área no asignada") . " a " . $area ?? " área no asignada" . ", se quitó del depósito: " . (($deposito = DepositoModel::find($impresora->deposito_id)) ? ', se quitó del depósito ' . ($deposito->nombre ?? '') : '') . ".";
                $historia->motivo = $request->input('editMotivo');
                $historia->componente_id = $impresora->id;
                $historia->tipo_dispositivo = 'Impresora'; // Tipo de transferencia
                $historia->tipo_id = 7; // Ajusta el tipo_id según sea necesario
                $historia->save();

                $impresora->area_id = $areaModel->findByName($area)->id;
                $impresora->deposito_id = null;
            } else {
                $areaNueva = new AreaModel();
                $areaNueva->nombre = $area;
                $areaNueva->visible = false;
                $areaNueva->save();

                $historia = new HistoriaModel();
                $historia->tecnico = $user->name;
                $historia->detalle = "Cambió el área de la impresora: " . $impresora->identificador . " - " . $impresora->nombre . " de " . (AreaModel::find($impresora->area_id)->nombre ?? "área no asignada") . " a " . $area ?? " área no asignada" . ", se quitó del depósito: " . (($deposito = DepositoModel::find($impresora->deposito_id)) ? ', se quitó del depósito ' . ($deposito->nombre ?? '') : '') . ".";
                $historia->motivo = $request->input('editMotivo');
                $historia->componente_id = $impresora->id;
                $historia->tipo_dispositivo = 'Impresora'; // Tipo de transferencia
                $historia->tipo_id = 7; // Ajusta el tipo_id según sea necesario
                $historia->save();

                $pc->area_id = $areaNueva->id;
                $impresora->deposito_id = null;
            }
        }

        if ($impresora->ip != $request->input('editIp')) {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = "Cambió la IP de la impresora: " . $impresora->identificador . " - " . $impresora->nombre . " de " . $impresora->ip . " a " . $request->input('editIp') . ".";
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora'; // Tipo de transferencia
            $historia->tipo_id = 7; // Ajusta el tipo_id según sea necesario
            $historia->save();
        }
        $impresora->ip = $request->input('editIp');

        if ($impresora->marca_modelo != $request->input('editMarca')) {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = "Cambió la marca y el modelo de la impresora: " . $impresora->identificador . " - " . $impresora->nombre . " de " . $impresora->marca_modelo . " a " . $request->input('editMarca') . ".";
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora'; // Tipo de transferencia
            $historia->tipo_id = 7; // Ajusta el tipo_id según sea necesario
            $historia->save();
        }
        $impresora->marca_modelo = $request->input('editMarca');

        if ($impresora->nombre != $request->input('editNombre')) {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = "Cambió el nombre de la impresora: " . $impresora->identificador . " - " . $impresora->nombre . " de " . $impresora->nombre . " a " . $request->input('editNombre') . ".";
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora'; // Tipo de transferencia
            $historia->tipo_id = 7; // Ajusta el tipo_id según sea necesario
            $historia->save();
        }
        $impresora->nombre = $request->input('editNombre');


        if ($impresora->identificador != $request->input('editIdentificador')) {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = "Cambió el identificador de la impresora: " . $impresora->identificador . " - " . $impresora->nombre . " de " . $impresora->identificador . " a " . $request->input('editIdentificador') . ".";
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora'; // Tipo de transferencia
            $historia->tipo_id = 7; // Ajusta el tipo_id según sea necesario
            $historia->save();
        }
        $impresora->identificador = $request->input('editIdentificador');

        $impresora->update();

        return redirect()->back()->with('success', 'Impresora editada correctamente.');
    }

    public function delete(Request $request)
    {
        $user = Auth::user();
        // Crear un nuevo registro
        $id = $request->input('deleteId');
        $impresora = ImpresoraModel::find($id);

        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Eliminó la impresora: " . $impresora->identificador . " - " . $impresora->nombre;
        $historia->motivo = $request->input('removeMotivo');
        $historia->tipo_id = 7;
        $historia->save();

        $transferencia = new ComponenteController();
        $transferencia->transferStateByPc($impresora->toner_id, 1, 4, "", null, null, false, false);

        $impresora->delete();

        return redirect()->back()->with('success', 'Impresora eliminada correctamente.');
    }

    public function getHistoria($id)
    {
        $historias = HistoriaModel::where('componente_id', $id)->get();
        return response()->json(['historia' => $historias]);
    }
}
