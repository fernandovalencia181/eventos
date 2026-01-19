<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = ['event_id', 'name', 'email', 'course', 'qr_token'];

    public function event()
    {
        return $this->belongsTo(Evento::class);
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
}
