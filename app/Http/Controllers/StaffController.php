<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Evento;
use App\Models\Checkin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isStaff()) {
                abort(403, 'Acceso denegado: solo para staff');
            }
            return $next($request);
        });
    }

    // Página principal de staff
    public function index()
    {
        return view('staff.index');
    }

    // Vista del scanner QR
    public function scanner()
    {
        $eventos = Evento::all();
        $staff = Staff::where('activo', true)->get();
        return view('staff.scanner', compact('eventos', 'staff'));
    }

    // Validar ticket escaneado
    public function validar(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string',
            'staff_id' => 'required|exists:staff,id',
            'evento_id' => 'required|exists:eventos,id',
        ]);

        // Buscar ticket (tu compañero crea la tabla tickets)
        $ticket = DB::table('tickets')
            ->where('codigo', $request->codigo)
            ->where('evento_id', $request->evento_id)
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'mensaje' => '❌ Ticket no encontrado'
            ], 404);
        }

        // Verificar si ya hizo check-in
        $checkinPrevio = Checkin::where('ticket_id', $ticket->id)->first();
        if ($checkinPrevio) {
            return response()->json([
                'success' => false,
                'mensaje' => '⚠️ Ya hizo check-in a las ' . $checkinPrevio->fecha_checkin->format('H:i')
            ], 400);
        }

        // Registrar check-in
        Checkin::create([
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
