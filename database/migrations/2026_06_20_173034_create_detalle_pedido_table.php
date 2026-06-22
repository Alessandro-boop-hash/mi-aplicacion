<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->restrictOnDelete();
            $table->string('talla', 10);
            $table->string('modelo');
            $table->unsignedInteger('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->timestamps();

            $table->index('pedido_id');
            $table->index('modelo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_pedido');
    }
};
