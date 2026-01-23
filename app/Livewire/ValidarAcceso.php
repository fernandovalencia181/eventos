<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use App\Models\Asistencia;
use App\Models\Evento;
use App\Models\User;

class ValidarAcceso extends Component
{
    public $evento_id;
    public $busqueda = '';
    public $tickets = [];
    public $ticket_seleccionado = null;
    public $mostrar_validacion = false;

    protected $listeners = ['validarTicket'];

    public function mount()
    {
        $this->evento_id = '';
    }

    public function buscarTicket()
    {
        $this->validate([
            'evento_id' => 'required|exists:eventos,id',
            'busqueda' => 'required|string|min:3',
        ]);

        $this->tickets = Ticket::with(['user', 'evento'])
            ->where('evento_id', $this->evento_id)
            ->where(function($q) {
                $q->where('token_seguridad_qr', 'like', '%' . $this->busqueda . '%')
                  ->orWhere('folio', 'like', '%' . $this->busqueda . '%')
                  ->orWhereHas('user', function($query) {
                      $query->where('name', 'like', '%' . $this->busqueda . '%')
                            ->orWhere('matricula', 'like', '%' . $this->busqueda . '%');
                  });
            })
            ->limit(20)
            ->get();
        
        if ($this->tickets->isEmpty()) {
            session()->flash('error', '❌ No se encontraron tickets con ese criterio.');
        }
    }

    public function seleccionarTicket($ticketId)
    {
        $this->ticket_seleccionado = Ticket::with(['user', 'evento', 'checkins.staff'])
            ->findOrFail($ticketId);
        $this->mostrar_validacion = true;
    }

    public function validarTicket($ticketId, $staffId)
    {
        $ticket = Ticket::findOrFail($ticketId);

        // Verificar si ya hizo check-in
        $checkinPrevio = Asistencia::where('ticket_id', $ticket->id)->first();
        
        if ($checkinPrevio) {
            session()->flash('error', '⚠️ Este ticket ya fue validado el ' . 
                $checkinPrevio->fecha_checkin->format('d/m/Y H:i') . ' por ' . 
                ($checkinPrevio->staff->nombre ?? 'Staff'));
            $this->mostrar_validacion = false;
            return;
        }

        // Registrar check-in
        Asistencia::create([
            'ticket_id' => $ticket->id,
            'staff_id' => $staffId,
            'evento_id' => $ticket->evento_id,
            'fecha_checkin' => now(),
            'metodo' => 'manual',
        ]);

        // Actualizar estado del ticket
        $ticket->update(['estado' => 'usado']);

        session()->flash('success', '✅ Acceso validado correctamente para ' . $ticket->user->name);
        $this->mostrar_validacion = false;
        $this->busqueda = '';
        $this->tickets = [];
        $this->ticket_seleccionado = null;
        
        $this->dispatch('ticketValidado');
    }

    public function cerrarModal()
    {
        $this->mostrar_validacion = false;
        $this->ticket_seleccionado = null;
    }

    public function render()
    {
        $eventos = Evento::where('fecha', '>=', now())->orderBy('fecha')->get();
        
        return view('livewire.validar-acceso', compact('eventos'));
    }
}
