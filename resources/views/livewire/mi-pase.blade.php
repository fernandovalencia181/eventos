<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8" wire:poll.1s="generateQr">
    
    <!-- Cabecera Estilo Proyecto -->
    <div class="mb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4 text-center sm:text-left">
            <div class="relative w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:rotate-3 transition-all duration-300 flex-shrink-0">
                <div class="absolute inset-0 bg-white/20 rounded-3xl backdrop-blur-sm"></div>
                <div class="relative z-10">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                </div>
            </div>
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Mi Pase Dinámico</h1>
                <p class="text-secondary-600 dark:text-secondary-400 mt-2">Utiliza este código para salir y re-ingresar al recinto.</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-primary-900 shadow-xl rounded-2xl border border-secondary-200 dark:border-primary-800 overflow-hidden">
        <div class="px-4 py-8 sm:p-10">
            @if(!$ticket)
                    <div class="text-center py-10">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Sin pase activo</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Aún no has ingresado al evento o tu entrada no requiere pase dinámico actualmente.
                            Usa tu PDF enviado por correo para el primer ingreso.
                        </p>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center space-y-4">
                        <div class="bg-white p-4 rounded-lg shadow-lg">
                            {!! $qrCodeSvg !!}
                        </div>
                        
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Este código expira en:</p>
                            <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 font-mono">
                                {{ $timeLeft }} s
                            </p>
                        </div>
                        
                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 max-w-xs mx-auto">
                            <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-1000 ease-linear" 
                                 style="width: {{ ($timeLeft / 30) * 100 }}%"></div>
                        </div>

                        <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 rounded-md text-sm max-w-md text-center">
                            <span class="font-bold">¡Alto!</span> No tomes captura de pantalla. Este código cambia constantemente por seguridad.
                        </div>

                        <div class="mt-2 text-xs text-gray-400 uppercase tracking-widest">
                            Estado: <span class="font-bold {{ $ticket->estado === 'adentro' ? 'text-green-500' : 'text-orange-500' }}">{{ strtoupper($ticket->estado) }}</span>
                        </div>
                    </div>
                    
                    <!-- Sección de Invitados -->
                    @if(count($guests) > 0)
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="text-md font-bold text-gray-900 dark:text-gray-100 mb-4">Mis Acompañantes</h4>
                        <div class="grid gap-4">
                            @foreach($guests as $guest)
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $guest['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $guest['phone'] ?? 'Sin teléfono' }}</p>
                                        <span class="text-xs px-2 py-0.5 rounded-full {{ $guest['ticket_status'] === 'adentro' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ strtoupper($guest['ticket_status']) }}
                                        </span>
                                    </div>
                                    
                                    @if($guest['whatsapp_link'] && in_array($guest['ticket_status'], ['afuera', 'anulada']))
                                        <a href="{{ $guest['whatsapp_link'] }}" target="_blank" class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-md transition text-sm font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                                <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                                            </svg>
                                            Enviar Pase
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                @endif
        </div>
    </div>
</div>
