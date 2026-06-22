<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventario_tela', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_tela');
            $table->string('color');
            $table->decimal('stock_actual_metros', 10, 2);
            $table->decimal('stock_minimo_metros', 10, 2);
            $table->timestamps();

            $table->index('tipo_tela');
            $table->index('color');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario_tela');
    }
};
