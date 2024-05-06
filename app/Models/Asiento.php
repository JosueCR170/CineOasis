<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asiento extends Model
{
    use HasFactory;
    
    protected $table='asientos';
    protected $fillable=['numero','fila','estado'];

    public function sala(){
        return $this->belongsTo(Sala::class,'id');
    }
}
