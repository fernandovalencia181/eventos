<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Evento;
use App\Models\Asistencia;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    // Página principal de staff (Dashboard)
    public function index()
    {
        $stats = [
            'validadas_hoy' => Asistencia::whereDate('created_at', today())->count(),
            'aforo_actual' => Asistencia::whereDate('created_at', today())->distinct('ticket_id')->count(),
            'invitados' => \App\Models\InvitadoEspecial::whereDate('created_at', today())->count(),
            'incidencias' => \App\Models\Incidencia::where('estado', 'pendiente')->count(),
            'eventos_activos' => Evento::where('fecha', '>=', now())->count(),
            'total_inscritos' => Ticket::where('estado', '!=', 'cancelada')->count(),
        ];

        // Eventos próximos
        $eventos = Evento::where('fecha', '>=', now())
            ->orderBy('fecha')
            ->take(5)
            ->withCount(['tickets as inscritos'])
            ->with(['checkins' => function($q) {
                $q->whereDate('created_at', today());
            }])
            ->get();

        // Últimas validaciones
        $ultimas_validaciones = Asistencia::with(['ticket.user', 'evento', 'staff'])
            ->latest()
            ->take(10)
            ->get();

        // Picos de llegada (por hora)
        $picos_llegada = Asistencia::whereDate('created_at', today())
            ->selectRaw('HOUR(created_at) as hora, COUNT(*) as total')
            ->groupBy('hora')
            ->orderBy('hora')
            ->get();

        return view('staff.dashboard', compact('stats', 'eventos', 'ultimas_validaciones', 'picos_llegada'));
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
        $eventos = Evento::where('fecha', '>=', now())->orderBy('fecha')->get();
        $invitados = \App\Models\InvitadoEspecial::with('evento')->latest()->get();
        
        return view('staff.invitados', compact('eventos', 'invitados'));
    }

    // Vista de incidencias
    public function incidencias()
    {
        $eventos = Evento::all();
        $incidencias = \App\Models\Incidencia::with(['evento', 'resuelto'])
            ->latest()
            ->get();
        
        $stats = [
            'pendientes' => \App\Models\Incidencia::where('estado', 'pendiente')->count(),
            'en_proceso' => \App\Models\Incidencia::where('estado', 'en_proceso')->count(),
            'resueltas' => \App\Models\Incidencia::where('estado', 'resuelta')->count(),
        ];
        
        return view('staff.incidencias', compact('eventos', 'incidencias', 'stats'));
    }

    // Lista de asistencia en vivo
    public function asistencia(Request $request)
    {
        $evento_id = $request->get('evento_id');
        
        // Només mostrar esdeveniments que tenen tickets/entrades creades
        $eventos = Evento::whereHas('tickets')
            ->where('fecha', '>=', now()->subDays(7))
            ->withCount('tickets')
            ->orderBy('fecha', 'desc')
            ->get();
        
        // Cargar todas las relaciones necesarias
        $query = Asistencia::with([
            'ticket' => function($q) {
                $q->with('user'); // Cargar explícitamente la relación user
            },
            'guest', // Cargar invitados especiales
            'evento'
        ]);
        
        if ($evento_id) {
            $query->where('evento_id', $evento_id);
        }
        
        $asistencias = $query->latest()->paginate(50);
        
        return view('staff.asistencia', compact('eventos', 'asistencias', 'evento_id'));
    }

    // Reportes y analytics
    public function reportes()
    {
        $eventos = Evento::withCount(['tickets as inscritos', 'checkins as asistentes'])
            ->where('fecha', '>=', now()->subMonths(3))
            ->orderBy('fecha', 'desc')
            ->get();

        $stats_generales = [
            'total_eventos' => Evento::count(),
            'total_asistentes' => Asistencia::distinct('ticket_id')->count(),
            'total_incidencias' => \App\Models\Incidencia::count(),
            'promedio_asistencia' => Evento::withCount(['tickets as inscritos', 'checkins as asistentes'])
                ->get()
                ->avg(function($evento) {
                    return $evento->inscritos > 0 ? ($evento->asistentes / $evento->inscritos) * 100 : 0;
                }),
        ];

        return view('staff.reportes', compact('eventos', 'stats_generales'));
    }

    // Exportar datos de asistencia
    public function exportar(Request $request)
    {
        $evento_id = $request->get('evento_id');
        
        $query = Asistencia::with([
            'ticket' => function($q) {
                $q->with('user');
            },
            'guest',
            'evento'
        ]);
        
        if ($evento_id) {
            $query->where('evento_id', $evento_id);
        }
        
        $asistencias = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'asistencias_' . ($evento_id ? 'evento_'.$evento_id.'_' : '') . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($asistencias) {
            $file = fopen('php://output', 'w');
            
            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, [
                'ID',
                'Evento',
                'Fecha Evento',
                'Asistente',
                'Matrícula',
                'Email',
                'Teléfono',
                'Fecha Check-in',
                'Hora Check-in',
                'Método Validación',
                'Tipo',
                'Folio Ticket'
            ]);
            
            // Data
            foreach ($asistencias as $asistencia) {
                // Obtener datos del usuario (ya sea de ticket o guest)
                $user = $asistencia->ticket?->user ?? $asistencia->guest;
                $userName = $user?->name ?? $user?->nombre ?? 'N/A';
                $userEmail = $user?->email ?? 'N/A';
                $userMatricula = $asistencia->ticket?->user?->matricula ?? ($asistencia->guest ? 'INVITADO' : 'N/A');
                $userTelefono = $user?->telefono ?? $user?->phone ?? 'N/A';
                $tipo = $asistencia->guest ? 'Invitado' : 'Ticket Regular';
                
                fputcsv($file, [
                    $asistencia->id,
                    $asistencia->evento->nombre ?? 'N/A',
                    $asistencia->evento->fecha ? $asistencia->evento->fecha->format('Y-m-d H:i') : 'N/A',
                    $userName,
                    $userMatricula,
                    $userEmail,
                    $userTelefono,
                    $asistencia->created_at->format('Y-m-d'),
                    $asistencia->created_at->format('H:i:s'),
                    ucfirst($asistencia->metodo),
                    $tipo,
                    $asistencia->ticket?->folio ?? $asistencia->guest_qr_token ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    // Registrar incidencia
    public function registrarIncidencia(Request $request)
    {
        $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'tipo' => 'required|string',
            'descripcion' => 'required|string',
        ]);

        \App\Models\Incidencia::create([
            'evento_id' => $request->evento_id,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'reportado_por' => Auth::id(),
            'estado' => 'pendiente',
        ]);

        return back()->with('success', '✅ Incidencia registrada correctamente');
    }

    // Registrar invitado especial
    public function registrarInvitado(Request $request)
    {
        $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email',
            'telefono' => 'nullable|string|max:20',
            'cargo' => 'nullable|string|max:255',
            'empresa' => 'nullable|string|max:255',
        ]);

        \App\Models\InvitadoEspecial::create($request->all());

        return back()->with('success', '✅ Invitado especial registrado correctamente');
    }

    // Emitir constancia
    public function emitirConstancia(Request $request)
    {
        $request->validate([
            'evento_id' => 'required|exists:eventos,id',
        ]);

        $evento = Evento::findOrFail($request->evento_id);
        
        // Obtener asistentes que cumplieron con la asistencia mínima
        $asistencias = Asistencia::where('evento_id', $evento->id)
            ->with('ticket.user')
            ->get();

        // Aquí implementarías la lógica para generar PDFs de constancias
        // Por ahora retornamos un JSON con los datos

        return response()->json([
            'success' => true,
            'mensaje' => 'Generando constancias para ' . $asistencias->count() . ' asistentes',
            'asistentes' => $asistencias->pluck('ticket.user.name'),
        ]);
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
        try {
            $request->validate([
                'codigo' => ['required', 'string', 'min:10'], // Validación flexible
                'evento_id' => 'required|exists:eventos,id',
            ]);

            // ✅ Usar staff_id del request o Auth::id() como fallback
            $staffId = $request->staff_id ?? Auth::id();

        // 🔍 Buscar en ENTRADAS (tickets) - per token_seguridad_qr o per ID (UUID)
        $ticket = Ticket::where('evento_id', $request->evento_id)
            ->where(function($q) use ($request) {
                $q->where('token_seguridad_qr', $request->codigo)
                  ->orWhere('id', $request->codigo);
            })
            ->first();

        // 🔍 Si no se encuentra, buscar en GUESTS (convidados)
        $guest = null;
        $esGuest = false;
        if (!$ticket) {
            $guest = \App\Models\Guest::where('qr_token', $request->codigo)
                ->whereHas('registration', function($q) use ($request) {
                    $q->where('event_id', $request->evento_id);
                })
                ->first();
            
            if ($guest) {
                $esGuest = true;
            }
        }

        // Si no se encuentra ni en tickets ni en guests
        if (!$ticket && !$guest) {
            return response()->json([
                'success' => false,
                'mensaje' => '❌ Entrada no vàlida o no pertany a aquest esdeveniment'
            ], 404);
        }

        // Verificar si ya hizo check-in
        if ($esGuest) {
            // Para guests, verificamos por qr_token directamente
            $checkinPrevio = Asistencia::where('guest_qr_token', $request->codigo)->first();
            if ($checkinPrevio) {
                return response()->json([
                    'success' => false,
                    'mensaje' => '⚠️ Convidat ja va fer check-in a les ' . $checkinPrevio->created_at->format('H:i')
                ], 400);
            }

            // Registrar check-in del guest
            Asistencia::create([
                'ticket_id' => null,
                'guest_qr_token' => $request->codigo,
                'staff_id' => $staffId,
                'evento_id' => $request->evento_id,
                'fecha_checkin' => now(),
                'metodo' => 'qr',
            ]);

            return response()->json([
                'success' => true,
                'mensaje' => '✅ Check-in exitós - Convidat: ' . $guest->name,
                'tipo' => 'guest',
                'nombre' => $guest->name
            ]);
        } else {
            // Para tickets normales
            $checkinPrevio = Asistencia::where('ticket_id', $ticket->id)->first();
            if ($checkinPrevio) {
                return response()->json([
                    'success' => false,
                    'mensaje' => '⚠️ Ja va fer check-in a les ' . $checkinPrevio->created_at->format('H:i')
                ], 400);
            }

            // Registrar check-in del ticket
            Asistencia::create([
                'ticket_id' => $ticket->id,
                'guest_qr_token' => null,
                'staff_id' => $staffId,
                'evento_id' => $request->evento_id,
                'fecha_checkin' => now(),
                'metodo' => 'qr',
            ]);

            return response()->json([
                'success' => true,
                'mensaje' => '✅ Check-in exitós',
                'tipo' => 'ticket',
                'ticket' => $ticket
            ]);
        }
        } catch (\Exception $e) {
            Log::error('Error validant entrada: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'mensaje' => '❌ Error del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    // Exportar datos de aforo
    public function exportarAforo(Request $request)
    {
        $evento_id = $request->get('evento_id');
        
        $query = Evento::withCount([
            'tickets as entradas_vendidas',
            'asistencias as personas_dentro'
        ]);
        
        if ($evento_id) {
            $query->where('id', $evento_id);
        }
        
        $eventos = $query->orderBy('fecha', 'desc')->get();
        
        $filename = 'aforo_' . ($evento_id ? 'evento_'.$evento_id.'_' : '') . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($eventos) {
            $file = fopen('php://output', 'w');
            
            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, [
                'ID',
                'Esdeveniment',
                'Data',
                'Hora',
                'Aforament Màxim',
                'Entrades Venudes',
                'Persones Dins',
                '% Ocupació',
                'Places Disponibles',
                'Estat'
            ]);
            
            // Data
            foreach ($eventos as $evento) {
                $aforo_usado = $evento->personas_dentro ?? 0;
                $aforo_total = $evento->aforo_maximo ?? 0;
                $porcentaje = $aforo_total > 0 ? ($aforo_usado / $aforo_total) * 100 : 0;
                $disponibles = $aforo_total - $aforo_usado;
                
                $estado = '';
                if ($porcentaje >= 100) {
                    $estado = 'COMPLET';
                } elseif ($porcentaje >= 90) {
                    $estado = 'GAIREBÉ COMPLET';
                } elseif ($porcentaje >= 75) {
                    $estado = 'ALTA OCUPACIÓ';
                } else {
                    $estado = 'DISPONIBLE';
                }
                
                fputcsv($file, [
                    $evento->id,
                    $evento->nombre,
                    $evento->fecha ? $evento->fecha->format('d/m/Y') : 'N/A',
                    $evento->fecha ? $evento->fecha->format('H:i') : 'N/A',
                    $aforo_total,
                    $evento->entradas_vendidas ?? 0,
                    $aforo_usado,
                    number_format($porcentaje, 2) . '%',
                    $disponibles,
                    $estado
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}

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
