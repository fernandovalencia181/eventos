<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InvitadoEspecial extends Model
{
    use HasFactory;

    protected $table = 'invitados_especiales';

    protected $fillable = [
        'evento_id',
        'nombre',
        'email',
        'telefono',
        'tipo',
        'folio',
        'qr_code',
        'ha_ingresado',
        'hora_ingreso',
        'registrado_por',
        'validado_por',
    ];

    protected $casts = [
        'ha_ingresado' => 'boolean',
        'hora_ingreso' => 'datetime',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function registrador()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function validador()
    {
        return $this->belongsTo(User::class, 'validado_por');
    }

    public static function generarFolio()
    {
        do {
            $folio = 'INV-' . strtoupper(Str::random(8));
        } while (self::where('folio', $folio)->exists());
        
        return $folio;
    }
}
