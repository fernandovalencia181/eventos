<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'evento_id',
        'folio',
        'qr_code',
        'estado',
        'fecha_compra',
        'fecha_uso',
        'validado_por',
    ];

    protected $casts = [
        'fecha_compra' => 'datetime',
        'fecha_uso' => 'datetime',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function validador()
    {
        return $this->belongsTo(User::class, 'validado_por');
    }

    public function asistencia()
    {
        return $this->hasOne(Asistencia::class);
    }

    // Generar folio único
    public static function generarFolio()
    {
        do {
            $folio = 'TKT-' . strtoupper(Str::random(8));
        } while (self::where('folio', $folio)->exists());
        
        return $folio;
    }

    // Validar ticket
    public function validar($staffId)
    {
        if ($this->estado !== 'activo') {
            return false;
        }

        $this->update([
            'estado' => 'usado',
            'fecha_uso' => now(),
            'validado_por' => $staffId,
        ]);

        // Crear registro de asistencia
        Asistencia::create([
            'ticket_id' => $this->id,
            'user_id' => $this->user_id,
            'evento_id' => $this->evento_id,
            'validado_por' => $staffId,
            'hora_entrada' => now(),
        ]);

        return true;
    }
}
