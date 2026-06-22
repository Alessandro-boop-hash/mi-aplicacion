<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('despachos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->restrictOnDelete();
            $table->date('fecha');
            $table->string('dni_receptor', 20);
            $table->string('nombre_receptor');
            $table->string('guia_remision_numero')->nullable();
            $table->string('etiqueta_path')->nullable();
            $table->timestamps();

            $table->index('pedido_id');
            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('despachos');
    }
};
