<?php

namespace App\Livewire\Staff;

use App\Models\Ticket;
use App\Models\InvitadoEspecial;
use App\Models\Evento;
use App\Models\User;
use App\Models\Asistencia;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ValidarEntrada extends Component
{
    public $eventoId;
    public $folio = '';
    public $busqueda = '';
    public $resultado = null;
    public $mensaje = '';
    public $tipoMensaje = ''; // success, error, warning
    public $mostrarModal = false;
    
    // Datos del ticket/invitado encontrado
    public $ticketEncontrado = null;
    public $usuarioEncontrado = null;
    public $invitadoEncontrado = null;

    public function mount($eventoId = null)
    {
        $this->eventoId = $eventoId;
    }

    public function escanearFolio()
    {
        $this->resetValidacion();

        if (empty($this->folio)) {
            $this->mostrarError('Por favor ingresa un folio');
            return;
        }

        $this->folio = strtoupper(trim($this->folio));

        // Buscar ticket normal
        if (str_starts_with($this->folio, 'TKT-')) {
            $this->validarTicket();
        }
        // Buscar invitado especial
        elseif (str_starts_with($this->folio, 'INV-')) {
            $this->validarInvitado();
        }
        else {
            $this->mostrarError('Formato de folio inválido');
        }
    }

    private function validarTicket()
    {
        $ticket = Ticket::with(['user', 'evento'])
            ->where('folio', $this->folio)
            ->first();

        if (!$ticket) {
            $this->mostrarError('Ticket no encontrado');
            return;
        }

        if ($this->eventoId && $ticket->evento_id != $this->eventoId) {
            $this->mostrarError('Este ticket no pertenece a este evento');
            return;
        }

        $this->ticketEncontrado = $ticket;
        $this->usuarioEncontrado = $ticket->user;

        // Verificar estado
        if ($ticket->estado === 'usado') {
            $this->tipoMensaje = 'warning';
            $this->mensaje = '⚠️ TICKET YA UTILIZADO - Validado el ' . $ticket->fecha_uso->format('d/m/Y H:i');
            $this->mostrarModal = true;
            return;
        }

        if ($ticket->estado === 'cancelado') {
            $this->mostrarError('❌ TICKET CANCELADO - No puede ingresar');
            return;
        }

        // Mostrar modal de confirmación
        $this->mostrarModal = true;
        $this->tipoMensaje = 'success';
        $this->mensaje = '✅ Ticket válido - Confirma para registrar entrada';
    }

    private function validarInvitado()
    {
        $invitado = InvitadoEspecial::with('evento')
            ->where('folio', $this->folio)
            ->first();

        if (!$invitado) {
            $this->mostrarError('Invitado no encontrado');
            return;
        }

        if ($this->eventoId && $invitado->evento_id != $this->eventoId) {
            $this->mostrarError('Este invitado no pertenece a este evento');
            return;
        }

        $this->invitadoEncontrado = $invitado;

        if ($invitado->ha_ingresado) {
            $this->tipoMensaje = 'warning';
            $this->mensaje = '⚠️ INVITADO YA INGRESÓ - ' . $invitado->hora_ingreso->format('d/m/Y H:i');
            $this->mostrarModal = true;
            return;
        }

        $this->mostrarModal = true;
        $this->tipoMensaje = 'success';
        $this->mensaje = '✅ Invitado válido - Confirma para registrar entrada';
    }

    public function confirmarEntrada()
    {
        if ($this->ticketEncontrado) {
            $resultado = $this->ticketEncontrado->validar(Auth::id());
            
            if ($resultado) {
                $this->mostrarExito('✅ Entrada registrada exitosamente');
                $this->emit('entradaRegistrada');
            } else {
                $this->mostrarError('Error al registrar entrada');
            }
        }
        elseif ($this->invitadoEncontrado) {
            $this->invitadoEncontrado->update([
                'ha_ingresado' => true,
                'hora_ingreso' => now(),
                'validado_por' => Auth::id(),
            ]);
            
            $this->mostrarExito('✅ Entrada de invitado registrada');
            $this->emit('entradaRegistrada');
        }

        $this->cerrarModal();
        $this->folio = '';
    }

    public function buscarManual()
    {
        if (empty($this->busqueda)) {
            return;
        }

        $resultados = User::where(function($query) {
            $query->where('name', 'like', '%' . $this->busqueda . '%')
                  ->orWhere('email', 'like', '%' . $this->busqueda . '%');
        })
        ->with(['tickets' => function($query) {
            if ($this->eventoId) {
                $query->where('evento_id', $this->eventoId);
            }
        }])
        ->limit(10)
        ->get();

        $this->resultado = $resultados;
    }

    public function seleccionarUsuario($userId)
    {
        $user = User::find($userId);
        
        if ($this->eventoId) {
            $ticket = Ticket::where('user_id', $userId)
                ->where('evento_id', $this->eventoId)
                ->first();
            
            if ($ticket) {
                $this->folio = $ticket->folio;
                $this->escanearFolio();
            } else {
                $this->mostrarError('Este usuario no tiene ticket para este evento');
            }
        }
    }

    private function resetValidacion()
    {
        $this->ticketEncontrado = null;
        $this->usuarioEncontrado = null;
        $this->invitadoEncontrado = null;
        $this->mensaje = '';
        $this->tipoMensaje = '';
    }

    private function mostrarError($mensaje)
    {
        $this->tipoMensaje = 'error';
        $this->mensaje = $mensaje;
    }

    private function mostrarExito($mensaje)
    {
        $this->tipoMensaje = 'success';
        $this->mensaje = $mensaje;
    }

    public function cerrarModal()
    {
        $this->mostrarModal = false;
    }

    public function render()
    {
        return view('livewire.staff.validar-entrada');
    }
}
