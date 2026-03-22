<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Actualizar todos los productos que tengan categoría 'tenis'
        // Por defecto los pasaremos a 'hombre' con tipo 'Tenis', 
        // ya que el usuario deberá editarlos manualmente si son de mujer.
        DB::table('prendas')
            ->where('categoria', 'tenis')
            ->update([
                'categoria' => 'hombre',
                'tipo' => 'Tenis'
            ]);

        // Modificar el enum de la tabla prendas para quitar 'tenis'
        Schema::table('prendas', function (Blueprint $table) {
            $table->enum('categoria', ['hombre', 'mujer', 'accesorios'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prendas', function (Blueprint $table) {
            $table->enum('categoria', ['hombre', 'mujer', 'accesorios', 'tenis'])->change();
        });
    }
};
