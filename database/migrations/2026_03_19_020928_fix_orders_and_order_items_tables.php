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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('orders', 'total')) {
                $table->decimal('total', 10, 2)->after('user_id');
            }
            if (!Schema::hasColumn('orders', 'metodo_pago')) {
                $table->string('metodo_pago')->after('total');
            }
            if (!Schema::hasColumn('orders', 'estado_pago')) {
                $table->enum('estado_pago', ['pendiente', 'pagado', 'cancelado'])->default('pendiente')->after('metodo_pago');
            }
            if (!Schema::hasColumn('orders', 'referencia_bancaria')) {
                $table->string('referencia_bancaria')->nullable()->after('estado_pago');
            }
            if (!Schema::hasColumn('orders', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('referencia_bancaria');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'order_id')) {
                $table->foreignId('order_id')->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('order_items', 'prenda_id')) {
                $table->foreignId('prenda_id')->after('order_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('order_items', 'cantidad')) {
                $table->integer('cantidad')->after('prenda_id');
            }
            if (!Schema::hasColumn('order_items', 'precio')) {
                $table->decimal('precio', 10, 2)->after('cantidad');
            }
            if (!Schema::hasColumn('order_items', 'talla')) {
                $table->string('talla')->nullable()->after('precio');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'total', 'metodo_pago', 'estado_pago', 'referencia_bancaria', 'payment_id']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'prenda_id', 'cantidad', 'precio', 'talla']);
        });
    }
};
