<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    use HasFactory;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function reportador()
    {
        return $this->belongsTo(User::class, 'reportado_por');
    }

    public function resolutor()
    {
        return $this->belongsTo(User::class, 'resuelto_por');
    }
}
