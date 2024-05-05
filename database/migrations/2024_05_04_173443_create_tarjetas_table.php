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
        Schema::create('tarjetas', function (Blueprint $table) {
            $table->id();
             //Llave foránea que se relaciona al usuario
             $table->foreignId('idUsuario')
             ->constrained('users')
             ->nullable()
             ->cascadeOnUpdate()
             ->cascadeOnDelete();

            $table->string('numero',25)->unique();
            $table->date('fechaVencimiento');
            $table->integer('codigo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarjetas');
    }
};
