<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesCombo extends Model
{
    use HasFactory;
    protected $table = 'detalles_combo';

    public function comida(){
        return $this->belongsTo(Comida::class,'idComida');
    }

    public function tickets(){
        return $this->belongsTo(Comida::class,'idTicket');
    }
}
