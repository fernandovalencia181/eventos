<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Staff extends Model
{
    protected $table = 'staff';

    protected $fillable = [
        'nombre',
        'email',
        'rol',
        'evento_id',
        'codigo_acceso',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Generar código automáticamente
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($staff) {
            if (empty($staff->codigo_acceso)) {
                $staff->codigo_acceso = strtoupper(Str::random(8));
            }
        });
    }

    // Relación con Evento
    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }
}
