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
        Schema::table('users', function (Blueprint $table) {
            // Campos para 2FA y Recuperación
            $table->string('two_factor_code')->nullable();
            $table->dateTime('two_factor_expires_at')->nullable();
            
            // Renombrar o asegurar campos de recuperación existentes
            if (!Schema::hasColumn('users', 'codigo_recuperacion')) {
                $table->string('codigo_recuperacion')->nullable();
            }
            if (!Schema::hasColumn('users', 'expira_codigo')) {
                $table->dateTime('expira_codigo')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['two_factor_code', 'two_factor_expires_at']);
        });
    }
};
