<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disenos', function (Blueprint $table) {
            $table->string('archivo_marca_agua_path')->nullable()->after('archivo_path');
        });
    }

    public function down(): void
    {
        Schema::table('disenos', function (Blueprint $table) {
            $table->dropColumn('archivo_marca_agua_path');
        });
    }
};
