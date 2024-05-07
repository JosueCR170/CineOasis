<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table='tickets';

    public function usuarios(){
        return $this->belongsTo(User::class,'idUsuario');
    }

    public function funciones(){
        return $this->belongsTo(Funcion::class,'idFuncion');
    }

    public function detallesTicket(){
        return $this->hasMany(DetallesTicket::class, 'idTicket');
    }

    public function detallesCombo(){
        return $this->hasMany(DetallesCombo::class, 'idTicket');
    }
}
