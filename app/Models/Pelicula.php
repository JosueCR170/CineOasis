<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelicula extends Model
{
    use HasFactory;
    protected $table='peliculas';
    
    protected static $idiomas=[
        'Español','Ingles','Frances','Portugues','Japones'
    ];

    protected static $subtitulos=[
        'Español','Ingles','Frances','Portugues','Japones','No Posee'
    ];

    protected static $clasificacion=[
        'G' => 'Para todos los públicos',
        'PG' => 'Con supervisión de los padres',
        'PG-13' => 'Con supervisión de los padres para menores de 13 años',
        'R' => 'Restringido y con supervisión de los padres para menores de 17 años',
        'NC-17' => 'Para mayores de 17 años'
    ];

    protected static $animacion=[
        '2D','3D','Stop-Motion'
    ];

    public function imagenes(){
        return $this->hasMany(Imagen::class, 'idPelicula');
    }

    public static function getIdiomas(){return self::$idiomas;}
    public static function getSubtitulos(){return self::$subtitulos;}
    public static function getClasificacion(){return self::$clasificacion;}
    public static function getAnimacion(){return self::$animacion;}
}
