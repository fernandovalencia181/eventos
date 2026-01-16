<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    // Estos son los campos que permitiremos guardar desde el formulario
    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha',
        'lugar',
        'aforo_maximo',
        'imagen',
        'precio_ticket', // Si lo tienes en la base de datos
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    // Relaciones
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class);
    }

    public function invitados()
    {
        return $this->hasMany(InvitadoEspecial::class);
    }

    public function espacios()
    {
        return $this->hasMany(EspacioEvento::class);
    }

    // Métricas
    public function asistenciasConfirmadas()
    {
        return $this->tickets()->where('estado', 'usado')->count();
    }

    public function aforoDisponible()
    {
        return $this->aforo_maximo - $this->asistenciasConfirmadas();
    }

    public function porcentajeOcupacion()
    {
        if ($this->aforo_maximo == 0) return 0;
        return round(($this->asistenciasConfirmadas() / $this->aforo_maximo) * 100, 2);
    }

    public function noShows()
    {
        return $this->tickets()->where('estado', 'activo')->count();
    }
}