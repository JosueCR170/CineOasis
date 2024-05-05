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
        Schema::create('peliculas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 40);
            $table->text('descripcion');
            $table->time('duracion');
            $table->enum('idioma', ['Español','Ingles','Frances','Portugues','Japones']);
            $table->enum('subtitulo',['Español','Ingles','Frances','Portugues','Japones']);
            $table->string('genero',40);
            $table->date('fechaEstreno');
            $table->integer('calificacionEdad');
            $table->string('calidad')->nullable();
            $table->string('director', 70)->nullable();
            $table->string('elenco', 160)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peliculas');
    }
};
