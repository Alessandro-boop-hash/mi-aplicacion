<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets_reclamo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->restrictOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->date('fecha_reclamo');
            $table->text('descripcion');
            $table->enum('calificacion', ['defecto_fabrica', 'uso_indebido', 'otro']);
            $table->enum('estado', ['abierto', 'en_revision', 'aprobado', 'rechazado', 'resuelto'])->default('abierto');
            $table->boolean('orden_cambio_generada')->default(false);
            $table->decimal('costo_cambio', 10, 2)->default(0);
            $table->timestamps();

            $table->index('pedido_id');
            $table->index('cliente_id');
            $table->index('estado');
            $table->index('fecha_reclamo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets_reclamo');
    }
};
