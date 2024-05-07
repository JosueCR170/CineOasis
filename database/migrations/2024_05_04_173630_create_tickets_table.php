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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('idUsuario')
            ->constrained('users')
            ->nullable()
            ->cascadeOnUpdate()
            ->cascadeOnDelete();

            $table->foreignId('idFuncion')
            ->constrained('funciones')
            ->nullable()
            ->cascadeOnUpdate()
            ->cascadeOnDelete();

            $table->integer('cantEntradas');
            $table->date('fechaCompra');
            $table->float('precioTotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
