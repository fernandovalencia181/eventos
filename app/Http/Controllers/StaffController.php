<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Evento;
use App\Models\Asistencia;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    // Página principal de staff (Dashboard)
    public function index()
    {
        $stats = [
            'validadas' => Asistencia::whereDate('created_at', today())->count(),
            'aforo_actual' => Asistencia::whereDate('created_at', today())->count(),
            'invitados' => 0, // Implementar cuando tengas tabla de invitados
            'incidencias' => 0, // Implementar cuando tengas tabla de incidencias
        ];

        return view('staff.index', compact('stats'));
    }

    // Vista del scanner QR
    public function scanner()
    {
        $eventos = Evento::all();
        $staff = Staff::where('activo', true)->get();
        return view('staff.scanner', compact('eventos', 'staff'));
    }

    // Vista de validación manual
    public function validacion()
    {
        $eventos = Evento::all();
        $ultimas = Asistencia::with(['ticket', 'evento'])
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'validadas_hoy' => Asistencia::whereDate('created_at', today())->count(),
            'pendientes' => Ticket::where('estado', 'generada')->count(),
            'rechazadas' => 0,
        ];

        return view('staff.validacion', compact('eventos', 'ultimas', 'stats'));
    }

    // Vista de aforo
    public function aforo()
    {
        $eventos = Evento::withCount(['tickets as entradas_vendidas'])->get();
        
        return view('staff.aforo', compact('eventos'));
    }

    // Vista de invitados
    public function invitados()
    {
        return view('staff.invitados');
    }

    // Vista de incidencias
    public function incidencias()
    {
        return view('staff.incidencias');
    }

    // Buscar entrada (AJAX)
    public function buscar(Request $request)
    {
        $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'busqueda' => 'required|string',
        ]);

        $ticket = Ticket::where('evento_id', $request->evento_id)
            ->where(function($q) use ($request) {
                $q->where('token_seguridad_qr', $request->busqueda)
                  ->orWhere('nombre_asistente', 'like', '%' . $request->busqueda . '%');
            })
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'mensaje' => '❌ Entrada no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'ticket' => $ticket
        ]);
    }

    // Validar entrada manualmente (AJAX)
    public function validarManual(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required',
        ]);

        $ticket = Ticket::findOrFail($request->ticket_id);

        // Verificar si ya fue validada
        $checkinPrevio = Asistencia::where('ticket_id', $ticket->id)->first();
        if ($checkinPrevio) {
            return response()->json([
                'success' => false,
                'mensaje' => '⚠️ Esta entrada ya fue validada'
            ], 400);
        }

        // Crear checkin
        Asistencia::create([
            'ticket_id' => $ticket->id,
            'staff_id' => Auth::id(),
            'evento_id' => $ticket->evento_id,
            'fecha_checkin' => now(),
            'metodo' => 'manual',
        ]);

        // Actualizar estado del ticket
        $ticket->update(['estado' => 'adentro']);

        return response()->json([
            'success' => true,
            'mensaje' => '✅ Entrada validada correctamente'
        ]);
    }

    // Validar ticket escaneado (QR)
    public function validar(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string',
            'staff_id' => 'required|exists:staff,id',
            'evento_id' => 'required|exists:eventos,id',
        ]);

        // Buscar ticket por token_seguridad_qr
        $ticket = Ticket::where('token_seguridad_qr', $request->codigo)
            ->where('evento_id', $request->evento_id)
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'mensaje' => '❌ Ticket no encontrado'
            ], 404);
        }

        // Verificar si ya hizo check-in
        $checkinPrevio = Asistencia::where('ticket_id', $ticket->id)->first();
        if ($checkinPrevio) {
            return response()->json([
                'success' => false,
                'mensaje' => '⚠️ Ya hizo check-in a las ' . $checkinPrevio->fecha_checkin->format('H:i')
            ], 400);
        }

        // Registrar check-in
        Asistencia::create([
            'ticket_id' => $ticket->id,
            'staff_id' => $request->staff_id,
            'evento_id' => $request->evento_id,
            'fecha_checkin' => now(),
            'metodo' => 'qr',
        ]);

        return response()->json([
            'success' => true,
            'mensaje' => '✅ Check-in exitoso',
            'ticket' => $ticket
        ]);
    }
}
