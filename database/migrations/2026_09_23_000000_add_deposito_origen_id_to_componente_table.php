<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega deposito_origen_id a la tabla componente.
     *
     * Este campo registra el depósito de origen de un componente
     * cuando su estado pasa a "En uso" (estado_id = 5) al ser asignado
     * a una PC. Permite devolver el componente al depósito correcto
     * si la PC se elimina o se le reemplaza el componente.
     *
     * Queda en null para componentes que nacen directamente en estado 5
     * (registrados sobre una PC sin pasar por stock), lo cual indica
     * que no hay depósito de origen porque nunca estuvieron en inventario.
     */
    public function up(): void
    {
        Schema::table('componente', function (Blueprint $table) {
            $table->integer('deposito_origen_id')->nullable()->after('deposito_id');
        });
    }

    public function down(): void
    {
        Schema::table('componente', function (Blueprint $table) {
            $table->dropColumn('deposito_origen_id');
        });
    }
};