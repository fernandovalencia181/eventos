<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class MisEntradas extends Component
{
    public function render()
    {
        // Traer tickets del usuario logueado
        $tickets = Ticket::with('evento')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.mis-entradas', compact('tickets'));
    }
}