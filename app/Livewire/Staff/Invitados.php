<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InvitadoEspecial;
use App\Models\Evento;
use App\Models\Ticket;
use App\Models\Registration;
use App\Models\Guest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class Invitados extends Component
{
    use WithPagination;

    public $search = '';
    public $evento_id_filter = '';
    
    // Form fields
    public $nombre;
    public $email;
    public $telefono;
    public $cargo;
    public $empresa;
    public $notas;
    public $generar_entrada = true; // Default to true for Generic Tickets

    public $showModal = false;
    
    // QR Modal
    public $viewingQr = false;
    public $currentQr = null;
    public $currentTicketId = null; 
    public $currentGuestName = '';
    
    // Dynamic QR props
    public $showDynamicQr = false;
    public $timeLeft = 20;

    public function mount()
    {
        // Set default event filter if user has one assigned
        if (Auth::user()->evento_id) {
            $this->evento_id_filter = Auth::user()->evento_id;
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->reset(['nombre', 'email', 'telefono', 'cargo', 'empresa', 'notas']);
        $this->generar_entrada = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function store()
    {
        $this->validate([
            'nombre' => 'required|min:3',
            'email' => 'nullable|email',
            'telefono' => 'nullable',
            // evento_id_filter is required for creating
        ]);

        $eventoId = $this->evento_id_filter ?: Evento::where('fecha', '>=', now())->orderBy('fecha')->first()->id;

        if (!$eventoId) {
            session()->flash('error', 'No hay un evento seleccionado para agregar el invitado.');
            return;
        }

        // NUEVA LÓGICA: Crear 'Guest' directamente (Manual Guest)
        // Usamos un token temporal o final
        $token = Str::random(32);

        $guest = Guest::create([
            'registration_id' => null, // Manual
            'name' => $this->nombre,
            'email' => $this->email,
            'phone' => $this->telefono,
            'company' => $this->empresa,
            'qr_token' => $token,
        ]);

        // 2. Generate Ticket if requested
        if ($this->generar_entrada) {
            $this->createTicket($guest, $eventoId);
        }

        $this->closeModal();
        session()->flash('success', 'Invitado agregado correctamente.');
    }

    public function createTicket(Guest $guest, $eventoId)
    {
        // Check if ticket already exists (simple check by name/event to avoid spamming, though names can be duplicate)
        // Ideally we would store ticket_id, but assuming we can't modify schema right now:
        // We just create a new ticket.
        
        Ticket::create([
            'id' => (string) Str::uuid(), // Ensure UUID if not auto-generated (Model says HasUuids so maybe auto)
            'evento_id' => $eventoId,
            'user_id' => null, // Generic
            'nombre_asistente' => $guest->name,
            // 'telefono' => removed from entradas table, access via guest relation
            'estado' => 'generada',
            'token_seguridad_qr' => $guest->qr_token,
            // We can store a reference in notas or just rely on name matching for display
        ]);
    }

    // SINCRONIZACIÓN DE DATOS ANTIGUOS
    public function syncLegacyData()
    {
        $count = 0;
        
        // Optimización: traer todos los qr_tokens de tickets
        $existingTokens = Ticket::pluck('token_seguridad_qr')->toArray();

        // 1. Sincronizar REGISTROS (Titulares)
        foreach(Registration::cursor() as $reg) {
            if (in_array($reg->qr_token, $existingTokens)) continue;

            Ticket::create([
                'evento_id' => $reg->event_id,
                'user_id' => $reg->user_id,
                'nombre_asistente' => $reg->name,
                // 'telefono' => $reg->phone, 
                'estado' => 'generada',
                'token_seguridad_qr' => $reg->qr_token,
            ]);
            $count++;
            $existingTokens[] = $reg->qr_token;
        }

        // 2. Sincronizar ACOMPAÑANTES (Guests)
        foreach(Guest::with('registration')->cursor() as $guest) {
            if (in_array($guest->qr_token, $existingTokens)) continue;
            
            if (!$guest->registration) continue; // Skip orphans

            Ticket::create([
                'evento_id' => $guest->registration->event_id,
                'user_id' => null, 
                'nombre_asistente' => $guest->name,
                // 'telefono' => $guest->phone,
                'estado' => 'generada',
                'token_seguridad_qr' => $guest->qr_token,
            ]);
            $count++;
            $existingTokens[] = $guest->qr_token;
        }

        session()->flash('success', "Se han sincronizado $count registros antiguos.");
        return redirect()->route('staff.invitados');
    }

    public function verQr($ticketId)
    {
        // $ticketId ya es el ID de la entrada directamente
        $ticket = Ticket::findOrFail($ticketId);
        
        $this->currentGuestName = $ticket->nombre_asistente;
        // Mostramos el ID del ticket, pero el QR se genera con el TOKEN que es lo que valida el scanner
        $this->currentTicketId = $ticket->id; 
        $this->showDynamicQr = false; // Reset to static

        // Generate Static QR Code
        // CORREGIDO: Usamos el token_seguridad_qr porque es lo que está en la BD como identificador externo "d89f..."
        // y lo que el scanner espera encontrar.
        $this->generateQrImage($ticket->token_seguridad_qr); 
        
        $this->viewingQr = true;
    }

    public function toggleQrType()
    {
        $this->showDynamicQr = !$this->showDynamicQr;
        $this->refreshDynamicQr();
    }
    
    public function setQrMode($isDynamic)
    {
        $this->showDynamicQr = $isDynamic;
        $this->refreshDynamicQr();
    }

    public function refreshDynamicQr()
    {
        if (!$this->viewingQr) return;

        $ticket = Ticket::find($this->currentTicketId);
        if (!$ticket) return;

        if ($this->showDynamicQr) {
            // Lógica MODO DINÁMICO (idéntica a MiPase)
            
            // Si expiró o no existe token dinámico, generar uno nuevo
            if (!$ticket->token_reentrada || $ticket->token_reentrada_expira < now()) {
                $newToken = Str::random(16);
                $ticket->update([
                    'token_reentrada' => $newToken,
                    'token_reentrada_expira' => now()->addSeconds(20) // 20 segundos
                ]);
                $this->timeLeft = 20;
            } else {
                // Calcular tiempo restante
                $this->timeLeft = $ticket->token_reentrada_expira->diffInSeconds(now());
            }

            $this->generateQrImage($ticket->token_reentrada);
        } else {
            // Lógica MODO ESTÁTICO: Usar el token original de seguridad
            $this->generateQrImage($ticket->token_seguridad_qr);
        }
    }

    private function generateQrImage($content)
    {
         $renderer = new ImageRenderer(
            new RendererStyle(300, 1),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrBase64 = base64_encode($writer->writeString($content ?: 'ERROR_NO_TOKEN'));
        $this->currentQr = 'data:image/svg+xml;base64,' . $qrBase64;
    }

    public function closeQr()
    {
        $this->viewingQr = false;
        $this->currentQr = null;
        $this->showDynamicQr = false; // Reset
    }

    public function descargarEntrada($ticketId)
    {
        // En este contexto, tratamos $ticketId como el ID de la entrada directamente
        $ticket = Ticket::findOrFail($ticketId);
        
        // Generate QR Code
        $contenido = json_encode(['id' => $ticket->id, 'sec' => $ticket->token_seguridad_qr]);
        
        $renderer = new ImageRenderer(
            new RendererStyle(200, 1),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrBase64 = base64_encode($writer->writeString($contenido));
        $qrImage = 'data:image/svg+xml;base64,' . $qrBase64;

        $pdf = Pdf::loadView('pdf.ticket', [
            'ticket' => $ticket,
            'qrCode' => $qrImage
        ]);
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'entrada-' . Str::slug($ticket->nombre_asistente) . '.pdf');
    }

    public function render()
    {
        $user = Auth::user();
        
        // CAMBIO PRINCIPAL: Ahora buscamos en la tabla de TICKETS (Entradas)
        // para traer a TODO el mundo (Usuarios, Acompañantes y VIPs)
        $query = Ticket::query();

        // 2. Filtrar por evento
        if ($this->evento_id_filter) {
            $query->where('evento_id', $this->evento_id_filter);
        } elseif ($user->evento_id) {
            $query->where('evento_id', $user->evento_id);
        }

        // 3. EXCLUIR ADMINS Y STAFF (Mostrar solo usuarios e invitados reales)
        $query->where(function($q) {
            $q->whereHas('user', function($u) {
                $u->whereNotIn('rol', ['admin', 'staff']);
            })
            ->orWhereNull('user_id'); // Invitados sin usuario asociado (Guests puros)
        });

        // 4. Búsqueda
        if ($this->search) {
            $term = $this->search;
            $query->where(function($q) use ($term) {
                $q->where('nombre_asistente', 'like', '%' . $term . '%')
                  // Búsqueda en Usuario (Email y Teléfono)
                  ->orWhereHas('user', function($u) use ($term) {
                      $u->where('email', 'like', '%' . $term . '%')
                        ->orWhere('phone', 'like', '%' . $term . '%');
                  })
                  // Búsqueda en Guest (Teléfono antiguo)
                  ->orWhereHas('guest', function($g) use ($term) {
                      $g->where('phone', 'like', '%' . $term . '%');
                  });
            });
        }

        $stats = [
            'total' => (clone $query)->count(),
            // 'pendientes' ya no tiene tanto sentido en Tickets generados, pero podríamos contar los escaneados si tuvieramos ese flag
            // Por ahora mostramos usuarios vs invitados
            'usuarios' => (clone $query)->whereNotNull('user_id')->count(),
            'invitados' => (clone $query)->whereNull('user_id')->count(),
        ];

        $invitados = $query->with('user', 'evento')->latest()->paginate(10);
        
        // Allow admin to select event
        $eventos = $user->evento_id 
            ? Evento::where('id', $user->evento_id)->get() 
            : Evento::where('fecha', '>=', now()->subDays(30))->orderBy('fecha', 'desc')->get();

        return view('staff.gestion-asistentes', [
            'invitados' => $invitados, // Mantenemos el nombre de variable para no romper la vista
            'eventos' => $eventos,
            'stats' => $stats
        ])->layout('components.layouts.app');
    }
}
