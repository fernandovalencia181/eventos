<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'eventos'; // Tabla manual

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha',
        'lugar',
        'aforo_maximo',
        'imagen',
        'precio_ticket', // Si lo tienes en la base de datos
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'evento_id');
    }
}