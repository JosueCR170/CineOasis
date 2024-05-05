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
        Schema::create('funciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idSala')
             ->constrained('salas')
             ->nullable()
             ->cascadeOnUpdate()
             ->cascadeOnDelete();
             $table->foreignId('idPelicula')
             ->constrained('peliculas')
             ->nullable()
             ->cascadeOnUpdate()
             ->cascadeOnDelete();
            $table->date("fecha");
            $table->time("horaInicio");
            $table->time("horaFinal");
            $table->float("precio");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funciones');
    }
};
