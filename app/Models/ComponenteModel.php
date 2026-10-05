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

    /**
     * Define la relación con el depósito físico actual del componente.
     */
    public function deposito()
    {
        return $this->belongsTo(DepositoModel::class, 'deposito_id');
    }

    /**
     * Define la relación con el depósito de procedencia de un componente que está en uso.
     */
    public function depositoOrigen()
    {
        return $this->belongsTo(DepositoModel::class, 'deposito_origen_id');
    }

    // Definir la relación con TipoComponenteModel
    /**
     * Define la relación con el tipo de componente.
     */
    public function tipo()
    {
        return $this->belongsTo(TipoComponenteModel::class, 'tipo_id');
    }

    /**
     * Define la relación con el estado actual del componente.
     */
    public function estado()
    {
        return $this->belongsTo(EstadoComponenteModel::class, 'estado_id');
    }



    /**
     * Obtiene los registros de componentes para consultas generales.
     */
    public function getComponentes()
    {
        return DB::table('componente')
            ->select('componente.*')
            ->get();
    }

    // En ComponenteModel.php
    // En ComponenteModel.php
    /**
     * Devuelve componentes de los tipos indicados que tienen stock y están disponibles.
     */
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

    /**
     * Devuelve componentes de los tipos indicados que se encuentran en uso.
     */
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

    /**
     * Busca componentes de los tipos indicados sin unidades disponibles.
     */
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

    /**
     * Prepara el catálogo de componentes que pueden seleccionarse al gestionar una PC, contemplando sus estados.
     */
    public function getComponenteByTipoForPc($tipoNombre, $tipoNombre2)
    {
        // Traer disponibles, sin stock 
        $filas = self::whereHas('tipo', function ($query) use ($tipoNombre, $tipoNombre2) {
            $query->where('nombre', $tipoNombre)
                ->orWhere('nombre', $tipoNombre2);
        })
            ->where(function ($query) {
                $query->where('estado_id', 4)->where('stock', '>', 0)
                    ->orWhere('estado_id', 7);
            })
            ->with(['tipo', 'deposito'])
            ->get();

        // Traer los En uso
        $enUso = self::whereHas('tipo', function ($query) use ($tipoNombre, $tipoNombre2) {
            $query->where('nombre', $tipoNombre)
                ->orWhere('nombre', $tipoNombre2);
        })
            ->where('estado_id', 5)
            ->with(['tipo', 'depositoOrigen'])
            ->get();

        // Por cada En uso, verificar si ya existe una fila hermana (estado 4 o 7)
        // con el mismo nombre+tipo+deposito_origen_id. Si no existe, crear un objeto
        // Sin stock virtual para que el técnico pueda cargarle stock desde el wizard.
        foreach ($enUso as $eu) {
            $yaExiste = $filas->first(function ($f) use ($eu) {
                return mb_strtolower(trim($f->nombre))  === mb_strtolower(trim($eu->nombre))
                    && (int) $f->tipo_id                === (int) $eu->tipo_id
                    && (int) $f->deposito_id            === (int) $eu->deposito_origen_id;
            });

            if (!$yaExiste && $eu->deposito_origen_id !== null && stripos($eu->nombre, 'no identificad') === false) {
                // Clonar la fila En uso como un objeto Sin stock virtual
                // (no se guarda en BD, solo viaja al blade/JS)
                $virtual = $eu->replicate();
                $virtual->id = -$eu->id;
                $virtual->estado_id = 7; // Sin stock
                $virtual->stock = 0;
                $virtual->deposito_id = $eu->deposito_origen_id;
                $virtual->deposito_origen_id = null;
                // Cargar la relación deposito manualmente
                $virtual->setRelation('deposito', $eu->depositoOrigen);

                $filas->push($virtual);
            }
        }

        return $filas;
    }

    public function getComponentesDisponiblesParaPc($tipoNombre, $tipoNombre2 = '')
    {
        return self::whereHas('tipo', function ($query) use ($tipoNombre, $tipoNombre2) {
            $query->where('nombre', $tipoNombre);

            if ($tipoNombre2 !== '') {
                $query->orWhere('nombre', $tipoNombre2);
            }
        })
            ->where('estado_id', 4) // Disponible
            ->where('stock', '>', 0)
            ->with(['tipo', 'deposito'])
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Realiza una consulta reutilizable sobre los componentes del inventario.
     */
    public function pcs()
    {
        return $this->belongsToMany(PcModel::class, 'componente_pc', 'componente_id', 'pc_id');
    }
}
