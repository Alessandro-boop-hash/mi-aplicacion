<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disenos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->restrictOnDelete();
            $table->foreignId('disenador_id')->constrained('users')->restrictOnDelete();
            $table->string('archivo_path');
            $table->enum('tipo_archivo', ['ai', 'png', 'pdf', 'jpg']);
            $table->unsignedInteger('resolucion_dpi')->nullable();
            $table->unsignedInteger('peso_kb')->nullable();
            $table->boolean('tiene_marca_agua')->default(false);
            $table->boolean('aprobado')->default(false);
            $table->boolean('bloqueado')->default(false);
            $table->timestamp('fecha_aprobacion')->nullable();
            $table->timestamps();

            $table->index('pedido_id');
            $table->index('disenador_id');
            $table->index('aprobado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disenos');
    }
};
