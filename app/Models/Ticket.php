<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Ticket extends Model
{
    use HasUuids; // Genera ID alfanumérico automático

    protected $table = 'entradas'; // Tabla manual

    protected $fillable = [
        'evento_id',
        'user_id',
        'nombre_asistente',
        'estado',
        'token_seguridad_qr'
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}