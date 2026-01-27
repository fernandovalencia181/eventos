<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = ['registration_id', 'name', 'qr_token'];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function evento()
    {
        return $this->hasOneThrough(Evento::class, Registration::class, 'id', 'id', 'registration_id', 'event_id');
    }
}
