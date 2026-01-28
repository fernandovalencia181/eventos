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
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'evento_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'event_id');
    }

    // Calcular cuánta gente hay registrada (Titulares + Acompañantes)
    public function getOcupacionAttribute()
    {
        $titulares = $this->registrations()->count();
        $acompanantes = \App\Models\Guest::whereHas('registration', function($query) {
            $query->where('event_id', $this->id);
        })->count();

        return $titulares + $acompanantes;
    }

    // Calcular cupos libres
    public function getLugaresDisponiblesAttribute()
    {
        return max(0, $this->aforo_maximo - $this->ocupacion);
    }
}
