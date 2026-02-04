<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'checkins';

    protected $fillable = [
        'ticket_id',
        'guest_qr_token', // ✅ Per convidats (guests)
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

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_qr_token', 'qr_token');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }
}
