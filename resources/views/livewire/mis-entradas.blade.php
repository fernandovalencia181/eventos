<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-primary-900">Mis Entradas</h1>
            <span class="bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-sm font-bold">
                {{ $tickets->count() }}
            </span>
        </div>

        @if($tickets->isEmpty())
            <div class="bg-white rounded-xl shadow p-12 text-center border border-secondary-200">
                <h3 class="text-secondary-500 text-lg">No tienes entradas registradas aún.</h3>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($tickets as $ticket)
                    <div class="ticket-card">
                        <div class="ticket-header">
                            <h2 class="ticket-title">{{ $ticket->evento->nombre }}</h2>
                        </div>

                        <div class="ticket-body">
                            <div class="mb-4 flex items-center text-secondary-600 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ \Carbon\Carbon::parse($ticket->evento->fecha)->format('d M Y - h:i A') }}
                            </div>

                            <div class="mb-4">
                                <p class="ticket-label">Asistente</p>
                                <p class="ticket-value">{{ $ticket->nombre_asistente ?? Auth::user()->name }}</p>
                            </div>

                            <div class="mb-6 flex justify-between items-center">
                                <span class="ticket-label">Estado</span>
                                <span class="@if($ticket->estado == 'adentro') badge-adentro 
                                             @elseif($ticket->estado == 'afuera') badge-afuera 
                                             @elseif($ticket->estado == 'anulada') badge-anulada 
                                             @else badge-default @endif">
                                    {{ strtoupper($ticket->estado) }}
                                </span>
                            </div>

                            <a href="{{ route('ticket.descargar', $ticket->id) }}" class="btn-download">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Descargar PDF
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>