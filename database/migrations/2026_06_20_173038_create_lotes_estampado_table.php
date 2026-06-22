<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lotes_estampado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lote_corte_id')->constrained('lotes_corte')->restrictOnDelete();
            $table->foreignId('operario_id')->constrained('users')->restrictOnDelete();
            $table->boolean('prueba_aprobada')->default(false);
            $table->unsignedInteger('piezas_estampadas');
            $table->unsignedInteger('piezas_con_defecto')->default(0);
            $table->date('fecha');
            $table->timestamps();

            $table->index('lote_corte_id');
            $table->index('operario_id');
            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lotes_estampado');
    }
};
