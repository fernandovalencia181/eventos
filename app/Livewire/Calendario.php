<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Calendario extends Component
{
    public $month;
    public $year;
    public $events = [];

    public function mount()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
        $this->loadEvents();
    }

    public function loadEvents()
    {
        // Cargar eventos donde el usuario tiene ticket
        $tickets = Ticket::with('evento')
            ->where('user_id', Auth::id())
            ->get();

        $this->events = $tickets->map(function ($ticket) {
            return [
                'id' => $ticket->evento->id,
                'title' => $ticket->evento->nombre,
                'date' => Carbon::parse($ticket->evento->fecha), // Asegúrate que en el modelo Evento sea 'fecha_hora' o 'fecha'
                'type' => 'evento'
            ];
        });
    }

    public function nextMonth()
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->addMonth();
        $this->month = $date->month;
        $this->year = $date->year;
    }

    public function prevMonth()
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->subMonth();
        $this->month = $date->month;
        $this->year = $date->year;
    }

    public function render()
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1);
        $daysInMonth = $date->daysInMonth;
        
        // Ajustar para que la semana empiece en Lunes (opcional, pero común en hispanoamérica)
        // Carbon: 0 (Dom) -> 6 (Sab). Si queremos Lunes=0, restamos 1 y ajustamos.
        // Pero usaremos el estándar de Carbon para simplificar la vista: 0=Domingo.
        $firstDayOfWeek = $date->dayOfWeek; 

        return view('livewire.calendario', [
            'daysInMonth' => $daysInMonth,
            'firstDayOfWeek' => $firstDayOfWeek,
            'currentDate' => $date
        ]);
    }
}
