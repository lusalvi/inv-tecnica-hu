<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EstadoComponenteModel;
use App\Models\HistoriaModel;
use Illuminate\Support\Facades\Auth;

class EstadoController extends Controller
{   
    

    /**
     * Prepara los datos necesarios para mostrar el listado y los filtros de la sección.
     */
    public function index(){
        $estados = EstadoComponenteModel::all();
        $historias = HistoriaModel::where('tipo_id', 6)
            ->orderBy('created_at', 'desc')
            ->get();


        // Pasar los datos a la vista
        return view('gest_estados', ['estados' => $estados, 'historias' => $historias]);
    }

    /**
     * Valida la solicitud y registra un nuevo elemento, aplicando las reglas de negocio correspondientes.
     *
     * @param Request $request Datos enviados por la solicitud HTTP.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'addNombre' => 'required|string|max:255|unique:estado_componente,nombre',
        ]);

        // Crear un nuevo registro
        $estado = new EstadoComponenteModel();
        $estado->nombre = $request->input('addNombre');
        $estado->save();

        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Creó el estado: ".$request->input('addNombre').".";
        $historia->motivo = "Creación de estado.";
        $historia->tipo_id = 6;
        $historia->save();

        return redirect()->back()->with('success', 'Estado creado correctamente.');
    }
    /**
     * Valida y actualiza los datos del elemento existente, conservando la consistencia de sus relaciones.
     *
     * @param Request $request Datos enviados por la solicitud HTTP.
     */
    public function edit(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'editNombre' => 'required|string|max:255|unique:estado_componente,nombre',
        ]);
        
        $id = $request->input('editId');
        $estado = EstadoComponenteModel::find($id);

        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Editó el nombre del estado: ".$estado->nombre." a ".$request->input('editNombre').".";
        $historia->motivo = $request->input('editMotivo');
        $historia->tipo_id = 6;
        $historia->save();

        $estado->nombre = $request->input('editNombre');
        $estado->update();

        

        return redirect()->back()->with('success', 'Estado editado correctamente.');
    }
    /**
     * Valida la operación y elimina el registro cuando las reglas del sistema lo permiten.
     *
     * @param Request $request Datos enviados por la solicitud HTTP.
     */
    public function delete(Request $request)
    {
        $user = Auth::user();
        // Crear un nuevo registro
        $id = $request->input('deleteId');
        $estado = EstadoComponenteModel::find($id);
        
        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Eliminó el estado: ".$estado->nombre.".";
        $historia->motivo = $request->input('removeMotivo');
        $historia->tipo_id = 6;
        $historia->save();

        $estado->delete();

        return redirect()->back()->with('success', 'Estado eliminado correctamente.');
    }
}
