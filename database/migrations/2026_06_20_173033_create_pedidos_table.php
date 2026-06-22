<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->date('fecha');
            $table->unsignedInteger('cantidad_total');
            $table->decimal('precio_total', 12, 2);
            $table->decimal('anticipo', 12, 2);
            $table->decimal('saldo_pendiente', 12, 2);
            $table->enum('estado', [
                'pendiente',
                'en_diseno',
                'en_produccion',
                'en_calidad',
                'en_empaque',
                'despachado',
                'entregado',
                'cancelado',
            ])->default('pendiente');
            $table->timestamps();

            $table->index('cliente_id');
            $table->index('estado');
            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
