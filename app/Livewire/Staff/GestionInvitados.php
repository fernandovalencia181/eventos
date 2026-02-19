<?php

namespace App\Livewire\Staff;

use App\Models\InvitadoEspecial;
use App\Models\Evento;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
// use SimpleSoftwareIO\QrCode\Facades\QrCode; // Instalar: composer require simplesoftwareio/simple-qrcode

class GestionInvitados extends Component
{
    public $eventoId;
    public $mostrarFormulario = false;
    
    // Formulario nuevo invitado
    public $nombre = '';
    public $email = '';
    public $telefono = '';
    public $tipo = 'ponente';

    public function mount($eventoId)
    {
        $this->eventoId = $eventoId;
    }

    public function guardarInvitado()
    {
        $this->validate([
            'nombre' => 'required|min:3',
            'email' => 'nullable|email',
            'tipo' => 'required',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'email.email' => 'El email debe ser válido',
        ]);

        $folio = InvitadoEspecial::generarFolio();

        $invitado = InvitadoEspecial::create([
            'evento_id' => $this->eventoId,
            'nombre' => $this->nombre,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'tipo' => $this->tipo,
            'folio' => $folio,
            'registrado_por' => Auth::id(),
        ]);

        // Generar QR - Requiere: composer require simplesoftwareio/simple-qrcode
        // Descomentar cuando se instale la librería:
        /*
        $qrPath = 'qr_codes/invitados/' . $folio . '.png';
        $qrImage = QrCode::format('png')->size(300)->generate($folio);
        Storage::disk('public')->put($qrPath, $qrImage);
        $invitado->update(['qr_code' => $qrPath]);
        */

        session()->flash('message', 'Invitado registrado exitosamente. Folio: ' . $folio);
        
        $this->resetFormulario();
        $this->mostrarFormulario = false;
    }

    public function eliminarInvitado($invitadoId)
    {
        $invitado = InvitadoEspecial::find($invitadoId);
        
        if ($invitado->ha_ingresado) {
            session()->flash('error', 'No se puede eliminar un invitado que ya ingresó');
            return;
        }
        
        // Eliminar QR del storage
        if ($invitado->qr_code) {
            Storage::disk('public')->delete($invitado->qr_code);
        }
        
        $invitado->delete();
        session()->flash('message', 'Invitado eliminado exitosamente');
    }

    private function resetFormulario()
    {
        $this->nombre = '';
        $this->email = '';
        $this->telefono = '';
        $this->tipo = 'ponente';
    }

    public function render()
    {
        $invitados = InvitadoEspecial::where('evento_id', $this->eventoId)
            ->with(['registrador', 'validador'])
            ->latest()
            ->get();

        return view('livewire.staff.gestion-invitados', [
            'invitados' => $invitados
        ]);
    }
}
