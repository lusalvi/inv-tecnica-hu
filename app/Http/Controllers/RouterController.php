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
use App\Models\routerModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RouterController extends Controller
{

    public function index()
    {

        // Obtener componentes por tipo con conexiones de tipo y depósito
        $routers = routerModel::with(['area', 'deposito'])->get();

        // Obtener otras entidades
        $historias = HistoriaModel::where('tipo_id', 9)
            ->orderBy('created_at', 'desc')
            ->get();
        $tipos = TipoComponenteModel::all();
        $depositos = DepositoModel::all();
        $areas = AreaModel::orderBy('nombre', 'asc')
            ->get();

        // Pasar los datos a la vista
        return view('gest_routers', [
            'historias' => $historias,
            'depositos' => $depositos,
            'areas' => $areas,
            'routers' => $routers
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
        $router = new RouterModel();
        $router->nombre = $request->input('addNombre');
        $router->identificador = $request->input('addIdentificador');
        $router->ip = $request->input('addIp');
        $router->deposito_id = $request->input('addDeposito');
        if (!$request->input('addDeposito')) {
            if ($request->input('addNroConsul')) {
                $area = AreaModel::find($request->input('addArea'))->nombre . " " . $request->input('addNroConsul');
            } else {
                $area = AreaModel::find($request->input('addArea'))->nombre;
            }
            if ($areaModel->findByName($area)) {
                $router->area_id = $areaModel->findByName($area)->id;
            } else {
                $areaNueva = new AreaModel();
                $areaNueva->nombre = $area;
                $areaNueva->visible = false;
                $areaNueva->save();

                $router->area_id = $areaNueva->id;
            }
        }
        $router->area_detalle = $request->input('addAreaDetalle');
        $router->marca_modelo = $request->input('addMarca');
        $router->save();

        // Guardar historia
        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Cargó el router: " . $request->input('addIdentificador') . " - " . $request->input('addNombre') . " - " . $request->input('addMarca') . ".";
        $historia->motivo = "Carga de router";
        $historia->componente_id = $router->id;
        $historia->tipo_dispositivo = 'Router'; // Tipo de transferencia
        $historia->tipo_id = 9;
        $historia->save();

        return redirect()->back()->with('success', 'Router guardada correctamente.');
    }


    public function edit(Request $request)
    {
        $user = Auth::user();
        $areaModel = new AreaModel();


        $id = $request->input('editId');
        $pc = PcModel::find($id);
        $router = routerModel::find($id);



        if ($request->input("editDetalle") != null || $request->input("editDetalle") != "") {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = $request->input("editDetalle");
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $router->id;
            $historia->tipo_dispositivo = 'Router'; // Tipo de transferencia
            $historia->tipo_id = 9; // Ajusta el tipo_id según sea necesario
            $historia->save();
        }

        if ($router->area_detalle != $request->input('editAreaDetalle') && $request->input("editAreaDetalle") != null && $request->input("editAreaDetalle") != "") {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = "Cambió el detalle de la ubicación del router: " . $router->identificador . " - " . $router->nombre . " de " . (($router->area_detalle) ?? 'detalle de área no especificado') . " a " . $request->input('editAreaDetalle') . ".";
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $router->id;
            $historia->tipo_dispositivo = 'Router'; // Tipo de transferencia
            $historia->tipo_id = 9; // Ajusta el tipo_id según sea necesario
            $historia->save();
            $router->area_detalle = $request->input('editAreaDetalle');
        } else {
            $router->area_detalle = "";
        }

        // Actualizar información básica de la router
        if ($router->deposito_id != $request->input('editDeposito') && $request->input('editDeposito') != null) {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = "Editó el depósito del router: " . $router->identificador . " - " . $router->nombre . " de " . (DepositoModel::find($router->deposito_id)->nombre ?? "depósito no asignado") . " a " . DepositoModel::find($request->input('editDeposito'))->nombre ?? "depósito no asignado" . ", se quitó del área: " . AreaModel::find($router->area_id)->nombre ?? "área no asignada" . ".";
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $router->id;
            $historia->tipo_dispositivo = 'Router'; // Tipo de transferencia
            $historia->tipo_id = 9; // Ajusta el tipo_id según sea necesario
            $historia->save();
            $router->deposito_id = $request->input('editDeposito');
            $router->area_id = null;
        }

        if ($router->area_id != $request->input('editArea') && $request->input('editArea') != null) {
            if ($request->input('editArea') == 27) {
                $area = AreaModel::find($request->input('editArea'))->nombre . " " . ($request->input('editNroConsul') ?? '');
            } else {
                $area = AreaModel::find($request->input('editArea'))->nombre;
            }
            if ($areaModel->findByName($area)) {
                $historia = new HistoriaModel();
                $historia->tecnico = $user->name;
                $historia->detalle = "Editó el área del router: " . $router->identificador . " - " . $router->nombre . " de " . (AreaModel::find($router->area_id)->nombre ?? "área no asignada") . " a " . $area ?? " área no asignada" . ", se quitó del depósito: " . DepositoModel::find($router->deposito_id)->nombre ?? "depósito no asignado" . ".";
                $historia->motivo = $request->input('editMotivo');
                $historia->componente_id = $router->id;
                $historia->tipo_dispositivo = 'Router'; // Tipo de transferencia
                $historia->tipo_id = 9; // Ajusta el tipo_id según sea necesario
                $historia->save();
                $router->area_id = $areaModel->findByName($area)->id;
            } else {
                $areaNueva = new AreaModel();
                $areaNueva->nombre = $area;
                $areaNueva->visible = false;
                $areaNueva->save();

                $historia = new HistoriaModel();
                $historia->tecnico = $user->name;
                $historia->detalle = "Editó el área del router: " . $router->identificador . " - " . $router->nombre . " de " . (AreaModel::find($router->area_id)->nombre ?? "área no asignada") . " a " . $area ?? " área no asignada" . ", se quitó del depósito: " . DepositoModel::find($router->deposito_id)->nombre ?? "depósito no asignado" . ".";
                $historia->motivo = $request->input('editMotivo');
                $historia->componente_id = $router->id;
                $historia->tipo_dispositivo = 'Router'; // Tipo de transferencia
                $historia->tipo_id = 9; // Ajusta el tipo_id según sea necesario
                $historia->save();

                $router->area_id = $areaNueva->id;
            }
            $router->deposito_id = null;
        }

        if ($router->ip != $request->input('editIp')) {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = "Editó la IP del router: " . $router->identificador . " - " . $router->nombre . " de " . $router->ip . " a " . $request->input('editIp') . ".";
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $router->id;
            $historia->tipo_dispositivo = 'Router'; // Tipo de transferencia
            $historia->tipo_id = 9; // Ajusta el tipo_id según sea necesario
            $historia->save();
        }
        $router->ip = $request->input('editIp');

        if ($router->marca_modelo != $request->input('editMarca')) {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = "Editó la marca y el modelo del router: " . $router->identificador . " - " . $router->nombre . " de " . $router->marca_modelo . " a " . $request->input('editMarca') . ".";
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $router->id;
            $historia->tipo_dispositivo = 'Router'; // Tipo de transferencia
            $historia->tipo_id = 9; // Ajusta el tipo_id según sea necesario
            $historia->save();
        }
        $router->marca_modelo = $request->input('editMarca');

        if ($router->nombre != $request->input('editNombre')) {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = "Editó el nombre del router: " . $router->identificador . " - " . $router->nombre . " de " . $router->nombre . " a " . $request->input('editNombre') . ".";
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $router->id;
            $historia->tipo_dispositivo = 'Router'; // Tipo de transferencia
            $historia->tipo_id = 9; // Ajusta el tipo_id según sea necesario
            $historia->save();
        }
        $router->nombre = $request->input('editNombre');


        if ($router->identificador != $request->input('editIdentificador')) {
            $historia = new HistoriaModel();
            $historia->tecnico = $user->name;
            $historia->detalle = "Editó el identificador del router: " . $router->identificador . " - " . $router->nombre . " de " . $router->identificador . " a " . $request->input('editIdentificador') . ".";
            $historia->motivo = $request->input('editMotivo');
            $historia->componente_id = $router->id;
            $historia->tipo_dispositivo = 'Router'; // Tipo de transferencia
            $historia->tipo_id = 9; // Ajusta el tipo_id según sea necesario
            $historia->save();
        }
        $router->identificador = $request->input('editIdentificador');

        $router->update();

        return redirect()->back()->with('success', 'Router editado correctamente.');
    }

    public function delete(Request $request)
    {
        $user = Auth::user();
        // Crear un nuevo registro
        $id = $request->input('deleteId');
        $router = routerModel::find($id);

        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Eliminó el router: " . $router->identificador . " - " . $router->nombre;
        $historia->motivo = $request->input('removeMotivo');
        $historia->tipo_id = 9;
        $historia->save();

        $router->delete();

        return redirect()->back()->with('success', 'Router eliminado correctamente.');
    }

    public function getHistoria($id)
    {
        $historias = HistoriaModel::where('componente_id', $id)->get();
        return response()->json(['historia' => $historias]);
    }
}
