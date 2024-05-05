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
        Schema::create('imagenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idPelicula')
            ->constrained('peliculas')
            ->nullable()
            ->cascadeOnUpdate()
            ->cascadeOnDelete();
            $table->binary('imagen')->nullable();
            $table->string('descripcion',55)->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE imagenes MODIFY imagen MEDIUMBLOB');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagenes');
    }
};
