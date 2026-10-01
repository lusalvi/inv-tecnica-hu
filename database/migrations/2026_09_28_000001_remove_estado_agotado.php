<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Elimina el estado "Agotado" (id = 6), que nunca se usó de forma real.
 *
 * Pasos:
 *   1. Reasigna cualquier fila de componente con estado_id = 6 a 7 (Sin Stock),
 *      que es la representación correcta de "stock cero disponible".
 *   2. Elimina el registro de estado_componente con id = 6.
 *
 * No se renumeran IDs. No se toca ninguna otra tabla.
 *
 * down() restaura el registro eliminado. Los componentes que fueron
 * reasignados no se revierten porque el estado 6 estaba sin uso real
 * y la reversión pondría datos en un estado que no tiene significado
 * operativo. Se documenta este límite para que quede claro.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            // 1 — Reasignar cualquier componente que tenga estado_id = 6
            //     a Sin Stock (7). En producción no debería haber ninguno,
            //     pero la migración lo cubre por seguridad.
            $reasignados = DB::table('componente')
                ->where('estado_id', 6)
                ->count();

            if ($reasignados > 0) {
                DB::table('componente')
                    ->where('estado_id', 6)
                    ->update(['estado_id' => 7]);
            }

            // 2 — Eliminar el estado Agotado.
            DB::table('estado_componente')
                ->where('id', 6)
                ->delete();
        });
    }

    public function down(): void
    {
        // Restaura el registro del estado Agotado.
        // Nota: los componentes que en up() se reasignaron de 6 a 7 no
        // se revierten, porque el estado 6 no tenía uso real. Si algún
        // componente quedó en 7 por la migración, sigue siendo correcto
        // desde el punto de vista del inventario.
        DB::table('estado_componente')->insert([
            'id'     => 6,
            'nombre' => 'Agotado',
        ]);
    }
};