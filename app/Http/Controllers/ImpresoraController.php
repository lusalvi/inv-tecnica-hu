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

        $toners     = $componentesModel->getComponenteByTipo('Toner', '');
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

        // Fix: la unicidad se valida contra la tabla impresora, no contra pc.
        $request->validate([
            'addNombre'       => 'required|string|max:255|unique:impresora,nombre',
            'addIdentificador' => 'required|string|max:255|unique:impresora,identificador',
        ]);

        $transferencia = new ComponenteController();
        $impresora     = new ImpresoraModel();

        $impresora->nombre       = $request->input('addNombre');
        $impresora->identificador = $request->input('addIdentificador');
        $impresora->ip           = $request->input('addIp');
        $impresora->deposito_id  = $request->input('addDeposito');

        if (! $request->input('addDeposito')) {
            $nombreArea = AreaModel::find($request->input('addArea'))->nombre;
            if ($request->input('addNroConsul')) {
                $nombreArea .= ' ' . $request->input('addNroConsul');
            }

            $area = $areaModel->findByName($nombreArea);
            if ($area) {
                $impresora->area_id = $area->id;
            } else {
                $areaNueva          = new AreaModel();
                $areaNueva->nombre  = $nombreArea;
                $areaNueva->visible = false;
                $areaNueva->save();
                $impresora->area_id = $areaNueva->id;
            }
        }

        $impresora->toner_id     = $transferencia->transferStateByPc(
            $request->input('addToner'),
            1,
            5,
            '',
            null,
            null,
            false,
            false
        );
        $impresora->marca_modelo = $request->input('addMarca');
        $impresora->save();

        $historia                  = new HistoriaModel();
        $historia->tecnico         = $user->name;
        $historia->detalle         = 'Cargó la impresora: '
            . $request->input('addIdentificador') . ' - '
            . $request->input('addNombre') . ' - '
            . $request->input('addMarca') . '.';
        $historia->motivo          = 'Carga de Impresora';
        $historia->componente_id   = $impresora->id;
        $historia->tipo_dispositivo = 'Impresora';
        $historia->tipo_id         = 7;
        $historia->save();

        return redirect()->back()->with('success', 'Impresora guardada correctamente.');
    }

    public function edit(Request $request)
    {
        $user      = Auth::user();
        $areaModel = new AreaModel();

        $id        = $request->input('editId');
        // Fix: eliminada la variable $pc (PcModel) que no corresponde a impresoras.
        $impresora = ImpresoraModel::find($id);

        if ($request->input('editDetalle') != null || $request->input('editDetalle') != '') {
            $historia                  = new HistoriaModel();
            $historia->tecnico         = $user->name;
            $historia->detalle         = $request->input('editDetalle');
            $historia->motivo          = $request->input('editMotivo');
            $historia->componente_id   = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora';
            $historia->tipo_id         = 7;
            $historia->save();
        }

        // Cambio de tóner: dentro de transacción, sin asignación duplicada afuera.
        if ($impresora->toner_id != $request->input('editToner')) {
            DB::transaction(function () use ($impresora, $request, $user) {
                $historia                  = new HistoriaModel();
                $historia->tecnico         = $user->name;
                $historia->detalle         = 'Cambió el toner de la impresora: '
                    . $impresora->identificador . ' - ' . $impresora->nombre
                    . ' de ' . (ComponenteModel::find($impresora->toner_id)->nombre ?? 'toner no asignado')
                    . ' a ' . (ComponenteModel::find($request->input('editToner'))->nombre ?? 'sin nombre') . '.';
                $historia->motivo          = $request->input('editMotivo');
                $historia->componente_id   = $impresora->id;
                $historia->tipo_dispositivo = 'Impresora';
                $historia->tipo_id         = 7;
                $historia->save();

                $transferencia = new ComponenteController();

                // El tóner retirado se consume: no vuelve al inventario porque
                // es un consumible. Se elimina su fila En uso.
                $transferencia->consumirComponenteEnUso($impresora->toner_id, 1);

                // El nuevo tóner sigue el flujo normal: Disponible → En uso.
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

                // Fix: asignación y save solo dentro de la transacción.
                // La asignación duplicada fuera de la transacción fue eliminada.
                $impresora->toner_id = $request->input('editToner');
                $impresora->save();
            });
        }

        // Cambio de depósito
        if (
            $impresora->deposito_id != $request->input('editDeposito')
            && $request->input('editDeposito') != null
        ) {
            $historia                  = new HistoriaModel();
            $historia->tecnico         = $user->name;
            $historia->detalle         = 'Cambió el depósito de la impresora: '
                . $impresora->identificador . ' - ' . $impresora->nombre
                . ' de ' . (DepositoModel::find($impresora->deposito_id)->nombre ?? 'depósito no asignado')
                . ' a '  . (DepositoModel::find($request->input('editDeposito'))->nombre ?? 'depósito no asignado')
                . (($area = AreaModel::find($impresora->area_id)) ? ', se quitó del área ' . ($area->nombre ?? '') : '') . '.';
            $historia->motivo          = $request->input('editMotivo');
            $historia->componente_id   = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora';
            $historia->tipo_id         = 7;
            $historia->save();

            $impresora->deposito_id = $request->input('editDeposito');
            $impresora->area_id     = null;
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

            $historia                  = new HistoriaModel();
            $historia->tecnico         = $user->name;
            $historia->detalle         = $detalleArea;
            $historia->motivo          = $request->input('editMotivo');
            $historia->componente_id   = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora';
            $historia->tipo_id         = 7;
            $historia->save();

            $areaExistente = $areaModel->findByName($nombreArea);
            if ($areaExistente) {
                $impresora->area_id = $areaExistente->id;
            } else {
                $areaNueva          = new AreaModel();
                $areaNueva->nombre  = $nombreArea;
                $areaNueva->visible = false;
                $areaNueva->save();
                // Fix: era $pc->area_id = ..., que nunca afectaba a la impresora.
                $impresora->area_id = $areaNueva->id;
            }
            $impresora->deposito_id = null;
        }

        // Cambio de IP
        if ($impresora->ip != $request->input('editIp')) {
            $historia                  = new HistoriaModel();
            $historia->tecnico         = $user->name;
            $historia->detalle         = 'Cambió la IP de la impresora: '
                . $impresora->identificador . ' - ' . $impresora->nombre
                . ' de ' . $impresora->ip . ' a ' . $request->input('editIp') . '.';
            $historia->motivo          = $request->input('editMotivo');
            $historia->componente_id   = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora';
            $historia->tipo_id         = 7;
            $historia->save();
        }
        $impresora->ip = $request->input('editIp');

        // Cambio de marca/modelo
        if ($impresora->marca_modelo != $request->input('editMarca')) {
            $historia                  = new HistoriaModel();
            $historia->tecnico         = $user->name;
            $historia->detalle         = 'Cambió la marca y el modelo de la impresora: '
                . $impresora->identificador . ' - ' . $impresora->nombre
                . ' de ' . $impresora->marca_modelo . ' a ' . $request->input('editMarca') . '.';
            $historia->motivo          = $request->input('editMotivo');
            $historia->componente_id   = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora';
            $historia->tipo_id         = 7;
            $historia->save();
        }
        $impresora->marca_modelo = $request->input('editMarca');

        // Cambio de nombre
        if ($impresora->nombre != $request->input('editNombre')) {
            $historia                  = new HistoriaModel();
            $historia->tecnico         = $user->name;
            $historia->detalle         = 'Cambió el nombre de la impresora: '
                . $impresora->identificador . ' - ' . $impresora->nombre
                . ' de ' . $impresora->nombre . ' a ' . $request->input('editNombre') . '.';
            $historia->motivo          = $request->input('editMotivo');
            $historia->componente_id   = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora';
            $historia->tipo_id         = 7;
            $historia->save();
        }
        $impresora->nombre = $request->input('editNombre');

        // Cambio de identificador
        if ($impresora->identificador != $request->input('editIdentificador')) {
            $historia                  = new HistoriaModel();
            $historia->tecnico         = $user->name;
            $historia->detalle         = 'Cambió el identificador de la impresora: '
                . $impresora->identificador . ' - ' . $impresora->nombre
                . ' de ' . $impresora->identificador . ' a ' . $request->input('editIdentificador') . '.';
            $historia->motivo          = $request->input('editMotivo');
            $historia->componente_id   = $impresora->id;
            $historia->tipo_dispositivo = 'Impresora';
            $historia->tipo_id         = 7;
            $historia->save();
        }
        $impresora->identificador = $request->input('editIdentificador');

        $impresora->update();

        return redirect()->back()->with('success', 'Impresora editada correctamente.');
    }

    public function delete(Request $request)
    {
        $user      = Auth::user();
        $id        = $request->input('deleteId');
        $impresora = ImpresoraModel::find($id);

        $historia          = new HistoriaModel();
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
