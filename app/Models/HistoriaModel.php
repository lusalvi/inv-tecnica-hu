<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriaModel extends Model
{
    use HasFactory;

    protected $fillable = ['tecnico', 'detalle', 'componente_id', 'created_at', 'updated_at', 'tipo_id', 'motivo', 'tipo_dispositivo'];

    protected $table = 'historia';

    /**
     * Obtiene los dispositivos modificados más recientemente a partir de los registros de historial.
     */
    public function getLastDevicesUpdated()
    {
        return HistoriaModel::whereNotNull('componente_id')
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('tipo_dispositivo', 'PC')
                        ->whereExists(function ($sub) {
                            $sub->select(DB::raw(1))
                                ->from('pc')
                                ->whereColumn('pc.id', 'historia.componente_id');
                        });
                })->orWhere(function ($q) {
                    $q->where('tipo_dispositivo', 'Impresora')
                        ->whereExists(function ($sub) {
                            $sub->select(DB::raw(1))
                                ->from('impresora')
                                ->whereColumn('impresora.id', 'historia.componente_id');
                        });
                })->orWhere(function ($q) {
                    $q->where('tipo_dispositivo', 'Telefono')
                        ->whereExists(function ($sub) {
                            $sub->select(DB::raw(1))
                                ->from('telefono')
                                ->whereColumn('telefono.id', 'historia.componente_id');
                        });
                })->orWhere(function ($q) {
                    $q->where('tipo_dispositivo', 'Router')
                        ->whereExists(function ($sub) {
                            $sub->select(DB::raw(1))
                                ->from('router')
                                ->whereColumn('router.id', 'historia.componente_id');
                        });
                });
            })
            ->select('componente_id', 'tipo_dispositivo', DB::raw('MAX(created_at) as created_at'))
            ->groupBy('componente_id', 'tipo_dispositivo')
            ->orderBy('created_at', 'DESC')
            ->limit(3)
            ->get();
    }
}
