<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesTicket extends Model
{
    use HasFactory;
    protected $table='detalles_ticket';

    public function asientos(){
        return $this->belongsTo(Asiento::class,'idAsiento');
    }

    public function tickets(){
        return $this->belongsTo(Ticket::class,'idTicket');
    }
}
