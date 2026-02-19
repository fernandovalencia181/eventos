<?php

namespace App\Livewire\Staff;

use App\Models\Evento;
use App\Models\Ticket;
use App\Models\Asistencia;
use App\Models\InvitadoEspecial;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PanelAforo extends Component
{
    public $eventoId;
    public $evento;
    
    // Métricas en tiempo real
    public $totalInscritos = 0;
    public $totalAsistentes = 0;
    public $aforoDisponible = 0;
    public $porcentajeOcupacion = 0;
    public $noShows = 0;
    public $invitadosEspeciales = 0;
    
    // Gráficas
    public $picosLlegada = [];
    public $ultimasEntradas = [];

    protected $listeners = ['entradaRegistrada' => 'actualizarMetricas'];

    public function mount($eventoId)
    {
        $this->eventoId = $eventoId;
        $this->cargarEvento();
        $this->actualizarMetricas();
    }

    public function cargarEvento()
    {
        $this->evento = Evento::findOrFail($this->eventoId);
    }

    public function actualizarMetricas()
    {
        $this->totalInscritos = Ticket::where('evento_id', $this->eventoId)->count();
        
        $this->totalAsistentes = Ticket::where('evento_id', $this->eventoId)
            ->where('estado', 'usado')
            ->count();
        
        $this->invitadosEspeciales = InvitadoEspecial::where('evento_id', $this->eventoId)
            ->where('ha_ingresado', true)
            ->count();
        
        $this->aforoDisponible = $this->evento->aforo_maximo - ($this->totalAsistentes + $this->invitadosEspeciales);
        
        $total = $this->totalAsistentes + $this->invitadosEspeciales;
        $this->porcentajeOcupacion = $this->evento->aforo_maximo > 0 
            ? round(($total / $this->evento->aforo_maximo) * 100, 1)
            : 0;
        
        $this->noShows = $this->totalInscritos - $this->totalAsistentes;
        
        $this->cargarPicosLlegada();
        $this->cargarUltimasEntradas();
    }

    private function cargarPicosLlegada()
    {
        // Agrupar entradas por hora
        $this->picosLlegada = Asistencia::where('evento_id', $this->eventoId)
            ->select(
                DB::raw('HOUR(hora_entrada) as hora'),
                DB::raw('COUNT(*) as cantidad')
            )
            ->groupBy('hora')
            ->orderBy('hora')
            ->get()
            ->map(function($item) {
                return [
                    'hora' => str_pad($item->hora, 2, '0', STR_PAD_LEFT) . ':00',
                    'cantidad' => $item->cantidad
                ];
            })
            ->toArray();
    }

    private function cargarUltimasEntradas()
    {
        $this->ultimasEntradas = Asistencia::where('evento_id', $this->eventoId)
            ->with(['user', 'validador'])
            ->latest('hora_entrada')
            ->limit(10)
            ->get();
    }

    public function exportarAsistentes()
    {
        $asistentes = Asistencia::where('evento_id', $this->eventoId)
            ->with(['user', 'ticket'])
            ->get();

        $filename = 'asistentes_' . $this->evento->nombre . '_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($asistentes) {
            $file = fopen('php://output', 'w');
            
            // Encabezados
            fputcsv($file, ['Nombre', 'Email', 'Folio', 'Hora Entrada', 'Validado Por']);
            
            foreach ($asistentes as $asistencia) {
                fputcsv($file, [
                    $asistencia->user->name,
                    $asistencia->user->email,
                    $asistencia->ticket->folio,
                    $asistencia->hora_entrada->format('d/m/Y H:i:s'),
                    $asistencia->validador->name,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        return view('livewire.staff.panel-aforo');
    }
}
