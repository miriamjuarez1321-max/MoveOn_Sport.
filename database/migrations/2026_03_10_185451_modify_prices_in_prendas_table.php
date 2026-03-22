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
        Schema::table('prendas', function (Blueprint $table) {
            $table->renameColumn('precio', 'precio_venta');
            $table->decimal('precio_compra', 10, 2)->after('stock')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prendas', function (Blueprint $table) {
            $table->renameColumn('precio_venta', 'precio');
            $table->dropColumn('precio_compra');
        });
    }
};
