<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insumos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->decimal('stock_actual', 10, 2);
            $table->decimal('stock_minimo', 10, 2);
            $table->foreignId('proveedor_id')->constrained('proveedores')->restrictOnDelete();
            $table->timestamps();

            $table->index('proveedor_id');
            $table->index('nombre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insumos');
    }
};
