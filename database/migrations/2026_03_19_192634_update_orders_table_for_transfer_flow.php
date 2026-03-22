<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Añadimos la columna comprobante_pago si no existe
        if (!Schema::hasColumn('orders', 'comprobante_pago')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('comprobante_pago')->nullable()->after('payment_id');
            });
        }

        // 2. Modificamos la columna estado_pago. 
        // En lugar de usar enum de Laravel directamente (que puede fallar en MySQL si hay valores antiguos),
        // usamos una consulta SQL directa para alterar el tipo de columna.
        DB::statement("ALTER TABLE orders MODIFY COLUMN estado_pago ENUM('pendiente_pago', 'pendiente_verificacion', 'pagado', 'cancelado', 'pendiente') DEFAULT 'pendiente_pago'");

        // 3. Actualizamos los registros existentes de 'pendiente' a 'pendiente_pago'
        DB::table('orders')->where('estado_pago', 'pendiente')->update(['estado_pago' => 'pendiente_pago']);

        // 4. Ahora que no hay registros 'pendiente', podemos limpiar el ENUM si queremos,
        // o simplemente dejarlo así. Para ser limpios, quitamos 'pendiente' de las opciones.
        DB::statement("ALTER TABLE orders MODIFY COLUMN estado_pago ENUM('pendiente_pago', 'pendiente_verificacion', 'pagado', 'cancelado') DEFAULT 'pendiente_pago'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('comprobante_pago');
            $table->enum('estado_pago', ['pendiente', 'pagado', 'cancelado'])->default('pendiente')->change();
        });
    }
};
