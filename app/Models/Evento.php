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
        'max_guests',
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

    public function checkins()
    {
        return $this->hasMany(Asistencia::class, 'evento_id');
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
        // Contar registros titulares
        $titulares = $this->registrations()->count();
        
        // Contar acompañantes asociados a esos registros
        $acompanantes = \App\Models\Guest::whereHas('registration', function($query) {
            $query->where('event_id', $this->id);
        })->count();
        
        // Sumar también los tickets generados manualmente o invitados especiales que no pasaron por registration
        // (Esto depende de tu lógica exacta, pero si usas Ticket como fuente de verdad, mejor contar Tickets)
        // Por ahora mantenemos la lógica de la migración anterior a base de Registration + Guest
        
        $totalOcupados = $titulares + $acompanantes;

        return max(0, $this->aforo_maximo - $totalOcupados);
    }
}
