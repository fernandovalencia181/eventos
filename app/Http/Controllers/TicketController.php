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
            'estado' => 'generada',
            'token_seguridad_qr' => Str::random(32),
        ]);

        return redirect()->route('mis.entradas')->with('success', '¡Entrada reservada con éxito!');
    }

    // --- FUNCIONES PARA EL SISTEMA DE PANTALLA DE CONTROL Y INVITADOS ---

    /**
     * Genera un QR dinámico para re-entrada o entrada de invitado.
     * Retorna el string del token.
     */
    public function getDynamicQr(Ticket $ticket)
    {
        // Seguridad: Solo el dueño o un invitado validado puede pedir esto.
        // Aquí asumimos validación vía Middleware o Policy previo, o chequeamos Auth::id()
        if (Auth::check() && $ticket->user_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }
        // Si no es Auth, podría ser acceso vía Guest Token (validar sesión/cookie)
        
        // Generar nuevo token valido por 60 segundos
        $token = Str::random(16);
        $ticket->update([
            'token_reentrada' => $token,
            'token_reentrada_expira' => now()->addSeconds(60)
        ]);

        return response()->json(['qr_token' => $token, 'expires_in' => 60]);
    }

    /**
     * Genera el enlace de invitado para compartir.
     */
    public function createGuestLink(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            return back()->with('error', 'No autorizado');
        }

        if (!$ticket->token_invitado) {
            $ticket->update(['token_invitado' => Str::random(40)]);
        }

        // Construir URL: /pase?t=TOKEN (o ruta dedicada)
        $url = route('guest.pass', ['t' => $ticket->token_invitado]);
        
        // Generar texto para WhatsApp
        $text = "Aquí tienes tu entrada para el evento. Accede con este enlace: " . $url;
        $whatsappUrl = "https://wa.me/?text=" . urlencode($text);

        return response()->json(['url' => $url, 'whatsapp_link' => $whatsappUrl]);
    }

    /**
     * Vista pública del Invitado
     */
    public function guestView(Request $request)
    {
        $token = $request->query('t');
        if (!$token) abort(404);

        $ticket = Ticket::where('token_invitado', $token)->firstOrFail();

        // Seguridad: Bloqueo de dispositivo
        $cookieName = 'guest_device_' . $ticket->id;
        $deviceCookie = $request->cookie($cookieName);

        if (!$ticket->dispositivo_invitado) {
            // Primer acceso: Vincular
            $deviceId = $deviceCookie ?? Str::random(40);
            $ticket->update(['dispositivo_invitado' => $deviceId]);
            // Encolar cookie si no existía
            if (!$deviceCookie) {
                cookie()->queue($cookieName, $deviceId, 60 * 24 * 30); // 30 días
            }
        } else {
            // Accesos subsecuentes: Validar
            if ($ticket->dispositivo_invitado !== $deviceCookie) {
               return view('errors.guest_locked'); // O abort(403)
            }
        }

        return view('guest.pass', compact('ticket'));
    }

    /**
     * Endpoint API para que la vista de invitado refresque su QR
     */
    public function guestDynamicQr(Request $request, $token)
    {
        $ticket = Ticket::where('token_invitado', $token)->firstOrFail();
        
        // Validar dispositivo nuevamente
        $cookieName = 'guest_device_' . $ticket->id;
        if ($ticket->dispositivo_invitado !== $request->cookie($cookieName)) {
            return response()->json(['error' => 'Dispositivo no autorizado'], 403);
        }

        $newToken = Str::random(16);
        $ticket->update([
            'token_reentrada' => $newToken,
            'token_reentrada_expira' => now()->addSeconds(60)
        ]);

        return response()->json(['qr_token' => $newToken]);
    }
}
