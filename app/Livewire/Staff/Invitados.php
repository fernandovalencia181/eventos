<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InvitadoEspecial;
use App\Models\Evento;
use App\Models\Ticket;
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

        // 1. Create InvitadoEspecial
        $invitado = InvitadoEspecial::create([
            'evento_id' => $eventoId,
            'nombre' => $this->nombre,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'cargo' => $this->cargo,
            'empresa' => $this->empresa,
            'notas' => $this->notas,
            'estado' => 'pendiente'
        ]);

        // 2. Generate Ticket if requested
        if ($this->generar_entrada) {
            $this->createTicket($invitado);
        }

        $this->closeModal();
        session()->flash('success', 'Invitado agregado correctamente.');
    }

    public function createTicket(InvitadoEspecial $invitado)
    {
        // Check if ticket already exists (simple check by name/event to avoid spamming, though names can be duplicate)
        // Ideally we would store ticket_id, but assuming we can't modify schema right now:
        // We just create a new ticket.
        
        Ticket::create([
            'id' => (string) Str::uuid(), // Ensure UUID if not auto-generated (Model says HasUuids so maybe auto)
            'evento_id' => $invitado->evento_id,
            'user_id' => null, // Generic
            'nombre_asistente' => $invitado->nombre,
            'estado' => 'generada',
            'token_seguridad_qr' => Str::random(32),
            // We can store a reference in notas or just rely on name matching for display
        ]);
    }

    public function descargarEntrada($invitadoId)
    {
        $invitado = InvitadoEspecial::findOrFail($invitadoId);
        
        // Find associated ticket (by name and event)
        $ticket = Ticket::where('evento_id', $invitado->evento_id)
                        ->where('nombre_asistente', $invitado->nombre)
                        ->latest()
                        ->first();

        if (!$ticket) {
            $this->createTicket($invitado);
            $ticket = Ticket::where('evento_id', $invitado->evento_id)
                        ->where('nombre_asistente', $invitado->nombre)
                        ->latest()
                        ->first();
        }

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
        }, 'entrada-' . Str::slug($invitado->nombre) . '.pdf');
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = InvitadoEspecial::query();

        if ($this->evento_id_filter) {
            $query->where('evento_id', $this->evento_id_filter);
        } elseif ($user->evento_id) {
            $query->where('evento_id', $user->evento_id);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('empresa', 'like', '%' . $this->search . '%');
            });
        }

        $stats = [
            'total' => (clone $query)->count(),
            'pendientes' => (clone $query)->where('estado', 'pendiente')->count(),
        ];

        $invitados = $query->latest()->paginate(10);
        
        // Allow admin to select event, staff is locked
        $eventos = $user->evento_id 
            ? Evento::where('id', $user->evento_id)->get() 
            : Evento::where('fecha', '>=', now()->subDays(30))->orderBy('fecha', 'desc')->get();

        return view('livewire.staff.invitados', [
            'invitados' => $invitados,
            'eventos' => $eventos,
            'stats' => $stats
        ]);
    }
}
