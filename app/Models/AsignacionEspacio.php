<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionEspacio extends Model
{
    use HasFactory;

    protected $table = 'asignaciones_espacio';

    protected $fillable = [
        'espacio_id',
        'user_id',
        'asignado_por',
    ];

    public function espacio()
    {
        return $this->belongsTo(EspacioEvento::class, 'espacio_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asignador()
    {
        return $this->belongsTo(User::class, 'asignado_por');
    }
}
