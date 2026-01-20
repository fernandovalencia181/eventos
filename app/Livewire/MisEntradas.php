<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;

class MisEntradas extends Component
{
    public function render()
    {
        // Traer registros del usuario logueado
        $registrations = Registration::with('event')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.mis-entradas', compact('registrations'));
    }
}