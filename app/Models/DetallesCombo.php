<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesCombo extends Model
{
    use HasFactory;
    protected $table = 'detalles_combo';
    public function peliculas(){
        //pertenece tambien a ticket
        return $this->belongsTo(Comida::class,'idComida');
    }
}
