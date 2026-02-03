<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    protected $table = 'incidencias';

    protected $fillable = [
        'evento_id',
        'user_id',
        'ticket_id',
        'tipo',
        'descripcion',
        'estado',
        'reportado_por',
        'resuelto_por',
        'fecha_resolucion',
        'solucion',
    ];

    protected $casts = [
        'fecha_resolucion' => 'datetime',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function reportadoPor()
    {
        return $this->belongsTo(User::class, 'reportado_por');
    }

    public function resuelto()
    {
        return $this->belongsTo(User::class, 'resuelto_por');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
