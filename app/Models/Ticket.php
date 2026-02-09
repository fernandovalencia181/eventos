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
        'token_seguridad_qr',
        'token_reentrada',
        'token_reentrada_expira',
        'token_invitado',
        'dispositivo_invitado',
    ];

    protected $casts = [
        'token_reentrada_expira' => 'datetime',
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

    /**
     * Relación con Guest (Acompañante antiguo) basada en el token QR.
     * Útil para recuperar datos como el teléfono que no están en la tabla tickets.
     */
    public function guest()
    {
        return $this->hasOne(Guest::class, 'qr_token', 'token_seguridad_qr');
    }
}