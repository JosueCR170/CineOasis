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
            $table->enum('subtitulo',['Español','Ingles','Frances','Portugues','Japones','No Posee']);
            $table->string('genero',20);
            $table->date('fechaEstreno');
            $table->enum('calificacionEdad',['G','PG','PG-13','R','NC-17']);
            $table->enum('animacion',['2D','3D','Stop-Motion']);
            $table->string('director', 70);
            $table->string('elenco', 160);

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
