<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    protected $table = 'incidencias';

    protected $fillable = [
        'evento_id',
        'staff_id',
        'tipo',
        'descripcion',
        'prioridad',
        'estado',
        'resuelta_por',
        'fecha_resolucion',
    ];

    protected $casts = [
        'fecha_resolucion' => 'datetime',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function resuelto()
    {
        return $this->belongsTo(User::class, 'resuelta_por');
    }
}
