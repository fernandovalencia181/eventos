<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Evento;
use App\Models\Asistencia;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StaffController extends Controller
{
    // Página principal de staff (Dashboard)
    public function index()
    {
        $user = Auth::user();
        $eventoId = $user->evento_id;

        $stats = [
            'validadas_hoy' => Asistencia::when($eventoId, fn($q) => $q->where('evento_id', $eventoId))
                ->whereDate('created_at', today())->count(),
            'aforo_actual' => Asistencia::when($eventoId, fn($q) => $q->where('evento_id', $eventoId))
                ->whereDate('created_at', today())->distinct('ticket_id')->count(),
            'invitados' => Ticket::when($eventoId, fn($q) => $q->where('evento_id', $eventoId))
                ->whereNull('user_id') // Invitados (no usuarios registrados)
                ->whereDate('created_at', today())->count(),
            'incidencias' => \App\Models\Incidencia::when($eventoId, fn($q) => $q->where('evento_id', $eventoId))
                ->where('estado', 'pendiente')->count(),
            'eventos_activos' => Evento::when($eventoId, fn($q) => $q->where('id', $eventoId))
                ->where('fecha', '>=', now())->count(),
            'total_inscritos' => Ticket::when($eventoId, fn($q) => $q->where('evento_id', $eventoId))
                ->where('estado', '!=', 'cancelada')->count(),
        ];

        // Evento Actual (Asignado) o Próximos
        $eventosQuery = Evento::where('fecha', '>=', now());
        if ($eventoId) {
            $eventosQuery->where('id', $eventoId);
        }
        $eventos = $eventosQuery->orderBy('fecha')
            ->take(5)
            ->withCount(['tickets as inscritos'])
            ->with(['checkins' => function($q) {
                $q->whereDate('created_at', today());
            }])
            ->get();

        // Últimas validaciones
        $validacionesQuery = Asistencia::with(['ticket.user', 'evento', 'staff']);
        if ($eventoId) {
            $validacionesQuery->where('evento_id', $eventoId);
        }
        $ultimas_validaciones = $validacionesQuery->latest()
            ->take(10)
            ->get();

        // Picos de llegada (por hora)
        $picosQuery = Asistencia::whereDate('created_at', today());
        if ($eventoId) {
            $picosQuery->where('evento_id', $eventoId);
        }
        $picos_llegada = $picosQuery->selectRaw('HOUR(created_at) as hora, COUNT(*) as total')
            ->groupBy('hora')
            ->orderBy('hora')
            ->get();

        return view('staff.dashboard', compact('stats', 'eventos', 'ultimas_validaciones', 'picos_llegada'));
    }

    // Vista del scanner QR (Dashboard version)
    public function scanner()
    {
        $user = Auth::user();
        
        // Si tiene evento asignado, usar ese directamente
        if ($user->evento_id) {
            $evento = Evento::find($user->evento_id);
            // Renderizamos la vista de escaneo directo (nueva vista o adaptamos scanView)
            return view('staff.scan', compact('evento'));
        }

        $eventos = Evento::all();
        $staff = Staff::where('activo', true)->get();
        return view('staff.scanner', compact('eventos', 'staff'));
    }
    
    // Vista del scanner QR (Access Control Version)
    public function scanView(Request $request)
    {
        // Si llega por parámetro
        if ($request->has('evento_id')) {
            $evento = Evento::find($request->evento_id);
            return view('staff.scan', compact('evento'));
        }
        
        // Si el usuario tiene evento asignado
        if (Auth::user()->evento_id) {
            $evento = Evento::find(Auth::user()->evento_id);
            return view('staff.scan', compact('evento'));
        }

        return view('staff.scan');
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
        if (!Auth::user()->hasPermission('access_guests')) {
            abort(403, 'No tienes permiso para acceder a la gestión de invitados.');
        }

        // Ya no necesitamos pasar nada, Livewire se encarga de todo
        return view('staff.invitados');
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
        $user = Auth::user();
        
        // Si el staff tiene evento asignado, forzar ese evento
        if ($user->evento_id) {
            $evento_id = $user->evento_id;
            
            // Solo traer el evento asignado
            $eventos = Evento::where('id', $user->evento_id)
                ->withCount('tickets')
                ->get();
        } else {
            // Si es admin, permitir filtro y mostrar lista completa
            $evento_id = $request->get('evento_id');
            
            $eventos = Evento::whereHas('tickets')
                ->where('fecha', '>=', now()->subDays(7))
                ->withCount('tickets')
                ->orderBy('fecha', 'desc')
                ->get();
        }
        
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
        if (!Auth::user()->hasPermission('access_guests')) {
            abort(403);
        }

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

    // Validar ticket escaneado (QR) - Método del Dashboard existente
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
                'mensaje' => '❌ Entrada no válida o no pertenece a este evento'
            ], 404);
        }

        // --- GESTIÓN DE INVITADOS (GUESTS) ---
        if ($esGuest) {
            // Para guests, verificamos por qr_token directamente
            $checkinPrevio = Asistencia::where('guest_qr_token', $request->codigo)->first();
            if ($checkinPrevio) {
                // Si hace menos de 2 minutos, mostramos advertencia pero no error 400
                if ($checkinPrevio->created_at->diffInMinutes(now()) < 2) {
                    return response()->json([
                        'success' => true, // True para mostrar en verde/amarillo
                        'mensaje' => '⚠️ Invitado ya validado hace un momento (' . $checkinPrevio->created_at->format('H:i:s') . ')',
                        'tipo' => 'guest',
                        'nombre' => $guest->name
                    ]);
                }
                
                return response()->json([
                    'success' => false,
                    'mensaje' => '⚠️ Invitado ya accedió a las ' . $checkinPrevio->created_at->format('H:i')
                ]); 
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
                'mensaje' => '✅ Check-in exitoso - Invitado: ' . $guest->name,
                'tipo' => 'guest',
                'nombre' => $guest->name
            ]);
        } 
        
        // --- GESTIÓN DE TICKETS REGULARES ---
        else {
            // Verificar estado y tiempos
            if ($ticket->estado === 'adentro') {
                // Buscamos el último movimiento
                $ultimoCheckin = Asistencia::where('ticket_id', $ticket->id)
                    ->latest('created_at')
                    ->first();

                // 1. Protección Anti-Passback Temporal (Rebote)
                // Si hace menos de 1 minuto que entró, es un doble escaneo accidental.
                if ($ultimoCheckin && $ultimoCheckin->created_at->diffInMinutes(now()) < 1) {
                    return response()->json([
                        'success' => true, // Success para no mostrar alerta roja
                        'mensaje' => '✅ Entrada ya registrada hace unos segundos (Pase adelante)',
                        'tipo' => 'ticket',
                        'ticket' => $ticket
                    ]);
                }

                // 2. Proceso de SALIDA (Cambio de estado)
                // Si ha pasado el tiempo de rebote, asumimos que está saliendo
                $ticket->update(['estado' => 'afuera']);
                
                return response()->json([
                    'success' => true,
                    'mensaje' => '👋 Salida registrada. Estado cambiado a "Afuera".',
                    'tipo' => 'ticket',
                    'ticket' => $ticket
                ]);
            }

            if ($ticket->estado === 'afuera') {
                return response()->json([
                    'success' => false,
                    'mensaje' => '⚠️ Usuario marcado como FUERA. Debe usar el QR Dinámico de reingreso (Mi Pase).',
                ]);
            }

            // Si el estado es 'generada' (O cualquier otro inicial), procedemos al Check-in
            
            // Registrar check-in del ticket
            Asistencia::create([
                'ticket_id' => $ticket->id,
                'guest_qr_token' => null,
                'staff_id' => $staffId,
                'evento_id' => $request->evento_id,
                'fecha_checkin' => now(),
                'metodo' => 'qr',
            ]);

            // Actualizar estado del ticket
            $ticket->update(['estado' => 'adentro']);

            return response()->json([
                'success' => true,
                'mensaje' => '✅ Entrada Válida - Bienvenido/a',
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

    /**
     * Procesa el escaneo de un código QR.
     * (Integrado para gestión de Re-entradas y Check-in/Check-out con estados)
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

        // Soporte para Guests en el método scan (nuevo)
        if (!$ticket) {
            $guest = \App\Models\Guest::where('qr_token', $qrCode)->first();
            if ($guest) {
                if ($mode === 'checkout') {
                     return response()->json(['success' => false, 'message' => 'Guest Checkout no implementado aún.'], 400);
                }
                
                $checkin = Asistencia::where('guest_qr_token', $qrCode)->first();
                if ($checkin) {
                    return response()->json(['success' => false, 'message' => 'El invitado ya ha ingresado.'], 400);
                }
                
                Asistencia::create([
                    'guest_qr_token' => $qrCode,
                    'staff_id' => Auth::id(),
                    'evento_id' => $guest->registration->event_id,
                    'fecha_checkin' => now(),
                    'metodo' => 'qr',
                ]);
                
                return response()->json(['success' => true, 'message' => 'Bienvenido Invitado: ' . $guest->name]);
            }
        }

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
             $ticket->update(['estado' => 'adentro']);
             // Aquí podríamos registrar tambien en Asistencia (modelo del compañero) para compatibilidad
             try {
                 Asistencia::create([
                     'ticket_id' => $ticket->id,
                     'evento_id' => $ticket->evento_id,
                     'staff_id' => Auth::id(), // Si es staff
                     'tipo' => 'entrada',
                     'metodo' => 'qr'
                 ]);
             } catch(\Exception $e) {}

             return response()->json(['success' => true, 'message' => 'Bienvenido (Check-in Inicial)', 'ticket' => $ticket]);
        }

        // Si es estado 'afuera' (Re-entrada)
        if ($ticket->estado === 'afuera') {
            // Verificar si el código usado es el estatico (NO PERMITIDO)
            if ($usedQr === $ticket->token_seguridad_qr) {
                return response()->json(['success' => false, 'message' => 'QR Estático anulado para re-entrada. Use Mi Pase QR Dinámico.'], 400);
            }

            $ticket->update(['estado' => 'adentro']);
            // Invalidar el token dinámico usado
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
