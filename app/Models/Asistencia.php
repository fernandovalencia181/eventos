<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'checkins';

    protected $fillable = [
        'ticket_id',
        'staff_id',
        'evento_id',
        'fecha_checkin',
        'metodo',
    ];

    protected $casts = [
        'fecha_checkin' => 'datetime',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }
}
