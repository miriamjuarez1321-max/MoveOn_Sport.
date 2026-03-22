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
        // Añadimos 'rechazado' al ENUM de estado_pago
        DB::statement("ALTER TABLE orders MODIFY COLUMN estado_pago ENUM('pendiente_pago', 'pendiente_verificacion', 'pagado', 'cancelado', 'rechazado') DEFAULT 'pendiente_pago'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertimos quitando 'rechazado' (asegurándonos de no tener registros con ese estado antes)
        DB::table('orders')->where('estado_pago', 'rechazado')->update(['estado_pago' => 'pendiente_pago']);
        DB::statement("ALTER TABLE orders MODIFY COLUMN estado_pago ENUM('pendiente_pago', 'pendiente_verificacion', 'pagado', 'cancelado') DEFAULT 'pendiente_pago'");
    }
};
