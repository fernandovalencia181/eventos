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
        $incidencias = \App\Models\Incidencia::with(['evento', 'staff', 'resuelto'])
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
        
        $eventos = Evento::where('fecha', '>=', now()->subDays(1))->orderBy('fecha')->get();
        
        $query = Asistencia::with(['ticket.user', 'staff', 'evento']);
        
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
        
        $query = Asistencia::with(['ticket.user', 'evento', 'staff']);
        
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
                'Validado por (Staff)',
                'Folio Ticket'
            ]);
            
            // Data
            foreach ($asistencias as $asistencia) {
                $user = $asistencia->ticket->user ?? null;
                fputcsv($file, [
                    $asistencia->id,
                    $asistencia->evento->nombre ?? 'N/A',
                    $asistencia->evento->fecha ? $asistencia->evento->fecha->format('Y-m-d H:i') : 'N/A',
                    $user ? $user->name : 'N/A',
                    $user && isset($user->matricula) ? $user->matricula : 'N/A',
                    $user ? $user->email : 'N/A',
                    $user && isset($user->telefono) ? $user->telefono : 'N/A',
                    $asistencia->created_at->format('Y-m-d'),
                    $asistencia->created_at->format('H:i:s'),
                    ucfirst($asistencia->metodo),
                    $asistencia->staff->nombre ?? 'N/A',
                    $asistencia->ticket->folio ?? 'N/A'
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
            'prioridad' => 'required|in:baja,media,alta,critica',
        ]);

        \App\Models\Incidencia::create([
            'evento_id' => $request->evento_id,
            'staff_id' => Auth::id(),
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'prioridad' => $request->prioridad,
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
        $request->validate([
            'codigo' => ['required', 'string', 'size:32', 'alpha_num'], // ✅ Validar formato exacto
            'staff_id' => 'nullable|exists:staff,id', // ✅ Hacer nullable
            'evento_id' => 'required|exists:eventos,id',
        ]);

        // ✅ Usar staff_id del request o Auth::id() como fallback
        $staffId = $request->staff_id ?? Auth::id();

        // Buscar ticket por token_seguridad_qr EXACTO
        $ticket = Ticket::where('token_seguridad_qr', $request->codigo)
            ->where('evento_id', $request->evento_id)
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'mensaje' => '❌ Entrada no vàlida o no pertany a aquest esdeveniment'
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
            'staff_id' => $staffId, // ✅ Usar la variable correcta
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
