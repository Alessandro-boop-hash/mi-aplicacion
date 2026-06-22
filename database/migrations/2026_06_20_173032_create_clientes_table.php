<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->string('nombre');
            $table->enum('tipo_documento', ['ruc', 'dni']);
            $table->string('numero_documento', 20);
            $table->string('email')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion')->nullable();
            $table->timestamps();

            $table->unique('numero_documento');
            $table->index('user_id');
            $table->index('nombre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
