<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lotes_corte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->restrictOnDelete();
            $table->foreignId('operario_id')->constrained('users')->restrictOnDelete();
            $table->decimal('metros_tela_usados', 10, 2);
            $table->decimal('merma_metros', 10, 2)->default(0);
            $table->unsignedInteger('piezas_obtenidas');
            $table->date('fecha');
            $table->timestamps();

            $table->index('pedido_id');
            $table->index('operario_id');
            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lotes_corte');
    }
};
