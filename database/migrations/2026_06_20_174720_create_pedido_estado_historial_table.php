<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_estado_historial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->restrictOnDelete();
            $table->enum('estado', [
                'pendiente',
                'en_diseno',
                'en_produccion',
                'en_calidad',
                'en_empaque',
                'despachado',
                'entregado',
                'cancelado',
            ]);
            $table->text('comentario')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index('pedido_id');
            $table->index('estado');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_estado_historial');
    }
};
