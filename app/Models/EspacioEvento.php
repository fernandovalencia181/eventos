<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EspacioEvento extends Model
{
    use HasFactory;

    protected $table = 'espacios_evento';

    protected $fillable = [
        'evento_id',
        'nombre',
        'capacidad',
        'ocupacion_actual',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionEspacio::class, 'espacio_id');
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'asignaciones_espacio', 'espacio_id', 'user_id')
                    ->withPivot('asignado_por')
                    ->withTimestamps();
    }

    public function capacidadDisponible()
    {
        return $this->capacidad - $this->ocupacion_actual;
    }

    public function porcentajeOcupacion()
    {
        if ($this->capacidad == 0) return 0;
        return round(($this->ocupacion_actual / $this->capacidad) * 100, 2);
    }
}
