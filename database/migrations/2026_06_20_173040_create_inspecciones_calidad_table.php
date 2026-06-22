<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspecciones_calidad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lote_costura_id')->constrained('lotes_costura')->restrictOnDelete();
            $table->foreignId('supervisor_id')->constrained('users')->restrictOnDelete();
            $table->decimal('tolerancia_cm', 4, 2)->default(1.5);
            $table->unsignedInteger('piezas_aprobadas');
            $table->unsignedInteger('piezas_rechazadas')->default(0);
            $table->string('firma_digital_path')->nullable();
            $table->date('fecha');
            $table->boolean('aprobado_para_empaque')->default(false);
            $table->timestamps();

            $table->index('lote_costura_id');
            $table->index('supervisor_id');
            $table->index('fecha');
            $table->index('aprobado_para_empaque');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspecciones_calidad');
    }
};
