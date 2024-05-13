<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asiento extends Model
{
    use HasFactory;
    
    protected $table='asientos';


    public function detallesTicket(){
        return $this->hasMany(DetallesTicket::class, 'idAsiento');
    }

    public function funcionesAsiento(){
        return $this->hasMany(FuncionAsiento::class, 'idAsiento');
    }
}
