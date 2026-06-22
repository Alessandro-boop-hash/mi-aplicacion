<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insumo_id')->constrained('insumos')->restrictOnDelete();
            $table->foreignId('proveedor_id')->constrained('proveedores')->restrictOnDelete();
            $table->decimal('cantidad', 10, 2);
            $table->enum('estado', ['generada', 'enviada', 'recibida'])->default('generada');
            $table->date('fecha');
            $table->timestamps();

            $table->index('insumo_id');
            $table->index('proveedor_id');
            $table->index('estado');
            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes_compra');
    }
};
