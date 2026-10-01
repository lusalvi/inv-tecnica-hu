<?php

namespace App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ComponenteModel extends Model
{
    use HasFactory;
    protected $fillable = ['tipo_id', 'nombre', 'stock', 'deposito_id'];

    protected $table = 'componente';
    public $timestamps = false;

    public function deposito()
    {
        return $this->belongsTo(DepositoModel::class, 'deposito_id');
    }

    public function depositoOrigen()
    {
        return $this->belongsTo(DepositoModel::class, 'deposito_origen_id');
    }

    // Definir la relación con TipoComponenteModel
    public function tipo()
    {
        return $this->belongsTo(TipoComponenteModel::class, 'tipo_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoComponenteModel::class, 'estado_id');
    }



    public function getComponentes()
    {
        return DB::table('componente')
            ->select('componente.*')
            ->get();
    }

    // En ComponenteModel.php
    // En ComponenteModel.php
    public function getComponenteByTipo($tipoNombre, $tipoNombre2)
    {
        return self::whereHas('tipo', function ($query) use ($tipoNombre, $tipoNombre2) {
            $query->where('nombre', $tipoNombre)
                ->orWhere('nombre', $tipoNombre2);
        })
            ->where('stock', '>', 0) // Filtrar por stock mayor a 0  
            ->where('estado_id', '=', 4)
            ->with(['tipo', 'deposito'])
            ->get();
    }

    public function getComponenteByTipoBystate($tipoNombre, $tipoNombre2)
    {
        return self::whereHas('tipo', function ($query) use ($tipoNombre, $tipoNombre2) {
            $query->where('nombre', $tipoNombre)
                ->orWhere('nombre', $tipoNombre2);
        })
            ->where('stock', '>', 0) // Filtrar por stock mayor a 0  
            ->where('estado_id', '=', 5)
            ->with(['tipo', 'deposito'])
            ->get();
    }

    public function getComponenteByTipoWithoutStock($tipoNombre, $tipoNombre2)
    {
        return self::whereHas('tipo', function ($query) use ($tipoNombre, $tipoNombre2) {
            $query->where('nombre', $tipoNombre)
                ->orWhere('nombre', $tipoNombre2);
        })
            ->where('estado_id', '=', 4)
            ->with(['tipo', 'deposito'])
            ->get();
    }

    public function getComponenteByTipoForPc($tipoNombre, $tipoNombre2)
{
    return self::whereHas('tipo', function ($query) use ($tipoNombre, $tipoNombre2) {
        $query->where('nombre', $tipoNombre)
            ->orWhere('nombre', $tipoNombre2);
    })
        ->where(function ($query) {
            $query->where(function ($q) {
                $q->where('estado_id', 4)
                    ->where('stock', '>', 0);
            })
            ->orWhere('estado_id', 7)
            ->orWhere('estado_id', 5);
        })
        ->with(['tipo', 'deposito'])
        ->get();
}

    public function pcs()
    {
        return $this->belongsToMany(PcModel::class, 'componente_pc', 'componente_id', 'pc_id');
    }
}
