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

    public function getLastDevicesUpdated()
    {
        return HistoriaModel::whereNotNull('componente_id')
            ->select('componente_id', 'tipo_dispositivo', 'created_at')
            ->orderBy('created_at', 'DESC')
            ->limit(4)
            ->get();
    }
}
