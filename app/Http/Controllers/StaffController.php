<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StaffController extends Controller
{
    /**
     * Muestra la vista de escaneo para el staff.
     */
    public function index()
    {
        // Retorna la vista donde estará el lector QR (JS)
        return view('staff.scan');
    }

    /**
     * Procesa el escaneo de un código QR.
     */
    public function scan(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
            'mode' => 'required|in:checkin,checkout'
        ]);

        $qrCode = $request->input('qr_code');
        $mode = $request->input('mode');

        // Buscar Ticket por Token Estático (PDF) o Dinámico (Re-entrada/Invitado)
        $ticket = Ticket::where('token_seguridad_qr', $qrCode)
                    ->orWhere(function($query) use ($qrCode) {
                        $query->where('token_reentrada', $qrCode)
                              ->where('token_reentrada_expira', '>', now());
                    })
                    ->first();

        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Código QR no válido o expirado.'], 404);
        }

        if ($mode === 'checkin') {
            return $this->handleCheckIn($ticket, $qrCode);
        } else {
            return $this->handleCheckOut($ticket);
        }
    }

    private function handleCheckIn(Ticket $ticket, $usedQr)
    {
        // Estado actual
        if ($ticket->estado === 'adentro') {
            return response()->json(['success' => false, 'message' => 'El asistente YA está dentro.'], 400);
        }

        if ($ticket->estado === 'anulada') {
            return response()->json(['success' => false, 'message' => 'Entrada ANULADA.'], 400);
        }

        // Si es estado 'generada' (Entrada Inicial)
        if ($ticket->estado === 'generada') {
             // Validar que NO use un token de re-entrada si todavía no entró nunca?
             // Si el invitado usa QR dinámico desde el principio, caerá aquí.
             
             $ticket->update(['estado' => 'adentro']);
             return response()->json(['success' => true, 'message' => 'Bienvenido (Check-in Inicial)', 'ticket' => $ticket]);
        }

        // Si es estado 'afuera' (Re-entrada)
        if ($ticket->estado === 'afuera') {
            // Para re-entrar, DEBE ser un QR dinámico válido (que ya comprobamos en la query inicial)
            // Verificar si el código usado es el estatico.
            if ($usedQr === $ticket->token_seguridad_qr) {
                return response()->json(['success' => false, 'message' => 'QR Estático anulado. Use QR Dinámico.'], 400);
            }

            $ticket->update(['estado' => 'adentro']);
            // Invalidar el token dinámico usado inmediatamente para evitar doble uso rapido
            $ticket->update(['token_reentrada' => null, 'token_reentrada_expira' => null]);
            
            return response()->json(['success' => true, 'message' => 'Bienvenido de nuevo (Re-Check-in)', 'ticket' => $ticket]);
        }

        return response()->json(['success' => false, 'message' => 'Estado desconocido.'], 400);
    }

    private function handleCheckOut(Ticket $ticket)
    {
        if ($ticket->estado === 'afuera') {
            return response()->json(['success' => false, 'message' => 'El asistente YA está afuera.'], 400);
        }
        
        if ($ticket->estado === 'generada') {
            return response()->json(['success' => false, 'message' => 'El asistente no ha ingresado aún.'], 400);
        }

        // Cambiar a afuera
        $ticket->update(['estado' => 'afuera']);

        return response()->json(['success' => true, 'message' => 'Salida registrada. Re-entrada habilitada.', 'ticket' => $ticket]);
    }
}
