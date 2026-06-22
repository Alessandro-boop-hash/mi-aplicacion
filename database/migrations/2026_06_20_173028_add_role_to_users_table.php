<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'cliente',
                'vendedor',
                'disenador',
                'operario_corte',
                'operario_estampado',
                'operario_costura',
                'supervisor_calidad',
                'almacenero',
                'admin',
            ])->default('cliente')->after('email');

            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropColumn('role');
        });
    }
};
