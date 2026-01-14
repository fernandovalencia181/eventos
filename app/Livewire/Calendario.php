<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Evento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Calendario extends Component
{
    public $month;
    public $year;
    public $events = [];
    public $eventsByDate = [];
    public $openDate = null; // Para controlar qué día está expandido

    public function mount()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
        $this->loadEvents();
    }

    public function loadEvents()
    {
        // CARGAR TODOS LOS EVENTOS (SOLO PARA ADMIN)
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acceso denegado');
        }

        $allEvents = Evento::get();

        $this->events = $allEvents->map(function ($evento) {
            return [
                'id' => $evento->id,
                'title' => $evento->nombre,
                'date' => Carbon::parse($evento->fecha),
                'type' => 'evento',
                // Puedes agregar más colores o lógica aquí
                'color' => 'bg-indigo-100 text-indigo-700' 
            ];
        });

        // Agrupar eventos por fecha 'Y-m-d' para fácil acceso en la vista
        $this->eventsByDate = $this->events->groupBy(function($event) {
            return $event['date']->format('Y-m-d');
        })->toArray();
    }

    public function toggleDate($dateStr)
    {
        if ($this->openDate === $dateStr) {
            $this->openDate = null;
        } else {
            $this->openDate = $dateStr;
        }
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
