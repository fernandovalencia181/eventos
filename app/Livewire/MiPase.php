<?php

namespace App\Livewire;

use App\Models\Ticket;
use App\Models\Registration;
use App\Models\Guest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Str;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class MiPase extends Component
{
    public $ticket;
    public $guests = []; // Nueva propiedad para almacenar info de acompañantes
    public $qrCodeSvg;
    public $timeLeft = 30;

    public function mount()
    {
        // Obtener el ticket activo del usuario
        $this->ticket = Ticket::where('user_id', Auth::id())
            ->whereIn('estado', ['adentro', 'afuera', 'anulada'])
            ->latest()
            ->first();

        if ($this->ticket) {
            $this->generateQr();
            $this->loadGuests(); // Cargar acompañantes
        }
    }
    
    public function loadGuests()
    {
        // Encontrar el Registration asociado a este evento y usuario
        $registration = Registration::where('user_id', Auth::id())
            ->where('event_id', $this->ticket->evento_id)
            ->with(['guests']) // Cargar relación guests
            ->first();

        if ($registration && $registration->guests) {
            // Mapeamos los guests con sus Tickets correspondientes
            $this->guests = $registration->guests->map(function($guest) {
                // Buscar el ticket del Guest usando el qr_token que comparten
                $guestTicket = Ticket::where('token_seguridad_qr', $guest->qr_token)->first();
                
                // Generar Link WhatsApp si tiene ticket y es necesario compartir
                $whatsappLink = null;
                if ($guestTicket && $guest->phone) {
                    // Generar token invitado si no existe
                     if (!$guestTicket->token_invitado) {
                        $guestTicket->update(['token_invitado' => Str::random(40)]);
                    }
                    $url = route('guest.pass', ['t' => $guestTicket->token_invitado]);
                    $text = "Hola {$guest->name}, aquí tienes tu entrada para el evento: " . $url;
                    $whatsappLink = "https://wa.me/{$guest->phone}?text=" . urlencode($text);
                }

                return [
                    'name' => $guest->name,
                    'phone' => $guest->phone,
                    'ticket_status' => $guestTicket ? $guestTicket->estado : 'no-generada',
                    'whatsapp_link' => $whatsappLink
                ];
            });
        }
    }

    public function generateQr()
    {
        if (!$this->ticket) return;

        // Si expiró o no existe token dinámico, generar uno nuevo
        if (!$this->ticket->token_reentrada || $this->ticket->token_reentrada_expira < now()) {
            $newToken = Str::random(16);
            $this->ticket->update([
                'token_reentrada' => $newToken,
                'token_reentrada_expira' => now()->addSeconds(30) // 30 segundos de vida
            ]);
            $this->timeLeft = 30;
        } else {
            // Calcular tiempo restante
            $this->timeLeft = $this->ticket->token_reentrada_expira->diffInSeconds(now());
        }

        // Generar SVG con BaconQrCode directamente (evitando conflictos de dependencias)
        $renderer = new ImageRenderer(
            new RendererStyle(250),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $this->qrCodeSvg = $writer->writeString($this->ticket->token_reentrada);
    }

    public function render()
    {
        // Polling cada segundo para actualizar contador y regenerar si hace falta
        if ($this->ticket && $this->ticket->token_reentrada_expira < now()) {
            $this->generateQr();
        }

        return view('livewire.mi-pase');
    }
}
