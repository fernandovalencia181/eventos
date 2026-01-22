<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvitadoEspecial extends Model
{
    protected $table = 'invitados_especiales';

    protected $fillable = [
        'evento_id',
        'nombre',
        'email',
        'telefono',
        'cargo',
        'empresa',
        'estado',
        'notas',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }
}
