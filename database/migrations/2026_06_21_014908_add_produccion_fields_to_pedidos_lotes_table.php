<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreignId('inventario_tela_id')
                ->nullable()
                ->after('estado')
                ->constrained('inventario_tela')
                ->restrictOnDelete();

            $table->index('inventario_tela_id');
        });

        Schema::table('lotes_corte', function (Blueprint $table) {
            $table->foreignId('inventario_tela_id')
                ->nullable()
                ->after('pedido_id')
                ->constrained('inventario_tela')
                ->restrictOnDelete();

            $table->index('inventario_tela_id');
        });

        Schema::table('lotes_estampado', function (Blueprint $table) {
            $table->boolean('procesamiento_completado')->default(false)->after('prueba_aprobada');
            $table->boolean('reposicion_solicitada')->default(false)->after('piezas_con_defecto');
        });
    }

    public function down(): void
    {
        Schema::table('lotes_estampado', function (Blueprint $table) {
            $table->dropColumn(['procesamiento_completado', 'reposicion_solicitada']);
        });

        Schema::table('lotes_corte', function (Blueprint $table) {
            $table->dropForeign(['inventario_tela_id']);
            $table->dropColumn('inventario_tela_id');
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign(['inventario_tela_id']);
            $table->dropColumn('inventario_tela_id');
        });
    }
};
