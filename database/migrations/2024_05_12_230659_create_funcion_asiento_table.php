<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('funcion_asiento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idFuncion')
            ->constrained('funciones')
            ->nullable()
            ->cascadeOnUpdate()
            ->cascadeOnDelete();
            $table->foreignId('idAsiento')
            ->constrained('asientos')
            ->nullable()
            ->cascadeOnUpdate()
            ->cascadeOnDelete();
            $table->boolean('estado');

            //$table->unique(['idFuncion', 'idAsiento']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcion_asiento');
    }
};
