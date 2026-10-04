<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\RolModel;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol_id',
        'pass_changed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Relación con el modelo de roles.
     */
    public function rol()
    {
        return $this->belongsTo(RolModel::class, 'rol_id');
    }

    /**
     * Devuelve el nombre del rol del usuario
     * según el valor almacenado en la base de datos.
     */
    public function getRolNombre(): string
    {
        return $this->rol?->nombre ?? '';
    }

    /**
     * Comprueba si el usuario tiene uno de los roles indicados.
     *
     * Acepta un string o un array de nombres de rol.
     *
     * Ejemplos:
     *   $user->hasRol('Tecnico')
     *   $user->hasRol(['Administrador', 'Super administrador'])
     */
    public function hasRol(string|array $roles): bool
    {
        $nombre = $this->getRolNombre();

        return in_array($nombre, (array) $roles, strict: true);
    }
}
