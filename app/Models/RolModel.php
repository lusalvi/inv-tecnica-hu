<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class RolModel extends Model
{
    protected $table = 'roles';

    /**
     * Relación con los usuarios que tienen este rol.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'rol_id');
    }

    /**
     * Devuelve el nombre del rol para mostrar en la interfaz.
     *
     * Los valores internos de la base de datos se mantienen sin modificar.
     */
    public function label(): string
    {
        return match ($this->nombre) {
            'Tecnico' => 'Técnico',
            'Super administrador' => 'Superadministrador',
            default => $this->nombre,
        };
    }
}
