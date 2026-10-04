<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoComponenteModel;
use App\Models\HistoriaModel;
use Illuminate\Support\Facades\Auth;

class TipoComponente extends Controller
{   
    

    public function index(){
        $tipos = TipoComponenteModel::all();
        $historias = HistoriaModel::where('tipo_id', 1)
            ->orderBy('created_at', 'desc')
            ->get();


        // Pasar los datos a la vista
        return view('gest_tipo_componente', ['tipos' => $tipos, 'historias' => $historias]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'addNombre' => 'required|string|max:255|unique:tipo_componente,nombre',
        ]);

        // Crear un nuevo registro
        $tipo_componente = new TipoComponenteModel();
        $tipo_componente->nombre = $request->input('addNombre');
        $tipo_componente->save();

        

        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Creó la categoría: ".$request->input('addNombre').".";
        $historia->motivo = "Creación de categoría.";
        $historia->tipo_id = 1;
        $historia->save();

        return redirect()->back()->with('success', 'Tipo de componente guardado correctamente.');
    }
    public function edit(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'editNombre' => 'required|string|max:255|unique:tipo_componente,nombre',
        ]);
        
        $id = $request->input('editId');
        $tipo_componente = TipoComponenteModel::find($id);

        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Editó el nombre de la categoría: ".$tipo_componente->nombre." a ".$request->input('editNombre').".";
        $historia->motivo = $request->input('editMotivo');
        $historia->tipo_id = 1;
        $historia->save();

        // Crear un nuevo registro
        
        $tipo_componente->nombre = $request->input('editNombre');
        $tipo_componente->update();

        

        return redirect()->back()->with('success', 'Tipo de componente editado correctamente.');
    }
    public function delete(Request $request)
    {
        $user = Auth::user();
        // Crear un nuevo registro
        $id = $request->input('deleteId');
        $tipo_componente = TipoComponenteModel::find($id);
        

        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Eliminó la categoría: ".$tipo_componente->nombre.".";
        $historia->motivo = $request->input("removeMotivo");
        $historia->tipo_id = 1;
        $historia->save();

        $tipo_componente->delete();

        return redirect()->back()->with('success', 'Categoría eliminada correctamente.');
    }
}
