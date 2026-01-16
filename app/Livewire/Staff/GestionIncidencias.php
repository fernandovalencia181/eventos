<?php

namespace App\Livewire\Staff;

use App\Models\Incidencia;
use App\Models\Evento;
use App\Models\User;
use App\Models\Ticket;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class GestionIncidencias extends Component
{
    public $eventoId;
    public $mostrarFormulario = false;
    
    // Formulario nueva incidencia
    public $tipo = 'perdida_qr';
    public $userId = null;
    public $ticketId = null;
    public $descripcion = '';
    public $busquedaUsuario = '';
    public $usuariosEncontrados = [];
    
    // Resolver incidencia
    public $incidenciaEditando = null;
    public $solucion = '';
    
    public $filtroEstado = 'todas'; // todas, pendiente, resuelto, escalado

    public function mount($eventoId)
    {
        $this->eventoId = $eventoId;
    }

    public function buscarUsuario()
    {
        if (strlen($this->busquedaUsuario) < 3) {
            $this->usuariosEncontrados = [];
            return;
        }

        $this->usuariosEncontrados = User::where(function($query) {
            $query->where('name', 'like', '%' . $this->busquedaUsuario . '%')
                  ->orWhere('email', 'like', '%' . $this->busquedaUsuario . '%');
        })
        ->limit(10)
        ->get();
    }

    public function seleccionarUsuario($userId)
    {
        $user = User::find($userId);
        $this->userId = $userId;
        $this->busquedaUsuario = $user->name;
        $this->usuariosEncontrados = [];
        
        // Buscar ticket del usuario para este evento
        $ticket = Ticket::where('user_id', $userId)
            ->where('evento_id', $this->eventoId)
            ->first();
        
        if ($ticket) {
            $this->ticketId = $ticket->id;
        }
    }

    public function guardarIncidencia()
    {
        $this->validate([
            'tipo' => 'required',
            'descripcion' => 'required|min:10',
        ], [
            'descripcion.required' => 'La descripción es obligatoria',
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres',
        ]);

        Incidencia::create([
            'evento_id' => $this->eventoId,
            'user_id' => $this->userId,
            'ticket_id' => $this->ticketId,
            'tipo' => $this->tipo,
            'descripcion' => $this->descripcion,
            'reportado_por' => Auth::id(),
            'estado' => 'pendiente',
        ]);

        session()->flash('message', 'Incidencia reportada exitosamente');
        
        $this->resetFormulario();
        $this->mostrarFormulario = false;
    }

    public function editarIncidencia($incidenciaId)
    {
        $this->incidenciaEditando = Incidencia::find($incidenciaId);
        $this->solucion = $this->incidenciaEditando->solucion ?? '';
    }

    public function resolverIncidencia()
    {
        $this->validate([
            'solucion' => 'required|min:10',
        ]);

        $this->incidenciaEditando->update([
            'estado' => 'resuelto',
            'solucion' => $this->solucion,
            'resuelto_por' => Auth::id(),
            'fecha_resolucion' => now(),
        ]);

        session()->flash('message', 'Incidencia resuelta exitosamente');
        
        $this->incidenciaEditando = null;
        $this->solucion = '';
    }

    public function escalarIncidencia($incidenciaId)
    {
        $incidencia = Incidencia::find($incidenciaId);
        $incidencia->update(['estado' => 'escalado']);
        
        session()->flash('message', 'Incidencia escalada a administración');
    }

    private function resetFormulario()
    {
        $this->tipo = 'perdida_qr';
        $this->userId = null;
        $this->ticketId = null;
        $this->descripcion = '';
        $this->busquedaUsuario = '';
        $this->usuariosEncontrados = [];
    }

    public function render()
    {
        $query = Incidencia::where('evento_id', $this->eventoId)
            ->with(['user', 'ticket', 'reportador', 'resolutor']);
        
        if ($this->filtroEstado !== 'todas') {
            $query->where('estado', $this->filtroEstado);
        }
        
        $incidencias = $query->latest()->get();

        return view('livewire.staff.gestion-incidencias', [
            'incidencias' => $incidencias
        ]);
    }
}
