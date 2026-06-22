<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lotes_costura', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lote_estampado_id')->constrained('lotes_estampado')->restrictOnDelete();
            $table->foreignId('operario_id')->constrained('users')->restrictOnDelete();
            $table->unsignedInteger('piezas_cosidas');
            $table->unsignedInteger('piezas_merma')->default(0);
            $table->date('fecha');
            $table->timestamps();

            $table->index('lote_estampado_id');
            $table->index('operario_id');
            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lotes_costura');
    }
};
