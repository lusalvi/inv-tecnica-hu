<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepositoModel;
use App\Models\HistoriaModel;
use Illuminate\Support\Facades\Auth;

class DepositoController extends Controller
{   
    

    public function index(){
        $depositos = DepositoModel::all();
        $historias = HistoriaModel::where('tipo_id', 2)
            ->orderBy('created_at', 'desc')
            ->get();


        // Pasar los datos a la vista
        return view('gest_depositos', ['depositos' => $depositos, 'historias' => $historias]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'addNombre' => 'required|string|max:255|unique:deposito,nombre'
        ]);

        // Crear un nuevo registro
        $deposito = new DepositoModel();
        $deposito->nombre = $request->input('addNombre');
        $deposito->save();

        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Creó el depósito: ".$request->input('addNombre').".";
        $historia->motivo = "Creación de depósito.";
        $historia->tipo_id = 2;
        $historia->save();

        return redirect()->back()->with('success', 'Depósito guardado correctamente.');
    }
    public function edit(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'editNombre' => 'required|string|max:255|unique:deposito,nombre',
        ]);
        
        $id = $request->input('editId');
        $deposito = DepositoModel::find($id);

        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Editó el nombre del depósito: ".$deposito->nombre." a ".$request->input('editNombre').".";
        $historia->motivo = $request->input('editMotivo');
        $historia->tipo_id = 2;
        $historia->save();

        $deposito->nombre = $request->input('editNombre');
        $deposito->update();

        

        return redirect()->back()->with('success', 'Depósito editado correctamente.');
    }
    public function delete(Request $request)
    {
        $user = Auth::user();
        $id = $request->input('deleteId');
        // Crear un nuevo registro
        $deposito = DepositoModel::find($id);
        
        $historia = new HistoriaModel();
        $historia->tecnico = $user->name;
        $historia->detalle = "Eliminó el depósito: ".$deposito->nombre.".";
        $historia->motivo = $request->input("removeMotivo");
        $historia->tipo_id = 2;
        $historia->save();

        $deposito->delete();

        return redirect()->back()->with('success', 'Depósito eliminado correctamente.');
    }
}
