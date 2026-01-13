<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function store(Request $request, Evento $evento)
    {
        // 1. Verificar si el usuario ya tiene entrada para este evento
        $existingTicket = Ticket::where('evento_id', $evento->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingTicket) {
            return redirect()->route('mis.entradas')->with('error', 'Ya tienes una entrada para este evento.');
        }

        // 2. Verificar aforo
        $ticketsCount = Ticket::where('evento_id', $evento->id)->count();
        if ($ticketsCount >= $evento->aforo_maximo) {
            return back()->with('error', 'El evento ha alcanzado su aforo máximo.');
        }

        // 3. Crear el ticket
        Ticket::create([
            'evento_id' => $evento->id,
            'user_id' => Auth::id(),
            'nombre_asistente' => Auth::user()->name, // Por defecto el usuario logueado
            'estado' => 'pendiente', // O 'generada'
            'token_seguridad_qr' => Str::random(32),
        ]);

        return redirect()->route('mis.entradas')->with('success', '¡Entrada reservada con éxito!');
    }
}
