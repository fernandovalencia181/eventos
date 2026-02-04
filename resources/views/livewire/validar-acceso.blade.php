<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header con Estilo Staff -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="relative w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-600 rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:rotate-3 transition-all duration-300">
                    <div class="absolute inset-0 bg-white/20 rounded-3xl backdrop-blur-sm"></div>
                    <div class="relative z-10">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Scanner de Acceso</h1>
                    <p class="text-secondary-600 dark:text-secondary-400 mt-2">Valida entradas con cámara o búsqueda manual</p>
                </div>
            </div>
        </div>

        <!-- Selector de Evento (Si no está predefinido) -->
        <div class="mb-6 bg-white dark:bg-primary-900 rounded-xl shadow-lg border border-secondary-200 dark:border-primary-800 p-4">
            <label class="block text-sm font-bold text-secondary-700 dark:text-secondary-300 mb-2">Evento Activo</label>
            <select wire:model.live="evento_id" class="w-full rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white focus:border-green-500 focus:ring-green-500">
                <option value="">-- Seleccionar Evento para Validar --</option>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}">{{ $evento->nombre }} ({{ \Carbon\Carbon::parse($evento->fecha)->format('d/m H:i') }})</option>
                @endforeach
            </select>
            @error('evento_id') <span class="text-red-500 text-xs mt-1 block">Selecciona un evento</span> @enderror
        </div>

        <div class="grid lg:grid-cols-2 gap-6">
            
            <!-- COLUMNA IZQUIERDA: CÁMARA -->
            <div class="bg-white dark:bg-primary-900 rounded-xl shadow-xl border border-secondary-200 dark:border-primary-800 overflow-hidden flex flex-col">
                <div class="p-4 border-b border-secondary-200 dark:border-primary-800 bg-secondary-50 dark:bg-primary-800/50 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-secondary-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                        Cámara QR
                    </h3>
                    <div id="camera-status" class="text-xs font-bold text-secondary-500 uppercase tracking-wide">Esperando...</div>
                </div>
                
                <div class="relative bg-black h-[400px] flex items-center justify-center group">
                    <div id="reader" class="w-full h-full object-cover"></div>
                    
                    <!-- Overlay de escaneo -->
                    <div class="absolute inset-0 pointer-events-none border-2 border-green-500/50 z-10 m-8 rounded-lg flex items-center justify-center">
                        <div class="w-full h-0.5 bg-green-500/80 animate-scan"></div>
                    </div>
                    
                    <button id="start-camera-btn" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-black/80 text-white cursor-pointer hover:bg-black/70 transition" onclick="startScanner()">
                        <div class="w-16 h-16 rounded-full bg-green-600 flex items-center justify-center mb-3 shadow-lg shadow-green-500/30 animate-pulse">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="font-bold text-lg">Tocar para Iniciar Cámara</span>
                    </button>
                </div>
                
                <div class="p-4 bg-secondary-50 dark:bg-primary-800/30 text-center">
                    <p class="text-xs text-secondary-500 dark:text-secondary-400">Apunta el código QR del asistente hacia la cámara.</p>
                </div>
            </div>

            <!-- COLUMNA DERECHA: MANUAL / RESULTADOS -->
            <div class="space-y-6">
                
                <!-- Búsqueda Manual -->
                <div class="bg-white dark:bg-primary-900 rounded-xl shadow-lg border border-secondary-200 dark:border-primary-800 p-6">
                    <h3 class="font-bold text-lg text-secondary-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Validación Manual
                    </h3>
                    <div class="flex gap-2">
                        <input type="text" wire:model.live.debounce.500ms="busqueda" placeholder="Buscar por Nombre, Folio o Token..." class="flex-1 rounded-xl border-secondary-300 dark:border-primary-600 dark:bg-primary-800 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                        <button wire:click="buscarTicket" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-bold transition">
                            Buscar
                        </button>
                    </div>

                    @if(!empty($tickets))
                        <div class="mt-4 space-y-2 max-h-60 overflow-y-auto custom-scrollbar">
                            @foreach($tickets as $ticket)
                                <div wire:click="seleccionarTicket({{ $ticket->id }})" class="p-3 rounded-lg border border-secondary-200 dark:border-primary-700 hover:bg-secondary-50 dark:hover:bg-primary-800/50 cursor-pointer transition flex justify-between items-center group">
                                    <div>
                                        <div class="font-bold text-secondary-900 dark:text-white">{{ $ticket->user->name }}</div>
                                        <div class="text-xs text-secondary-500">{{ $ticket->folio }}</div>
                                    </div>
                                    <div class="text-blue-600 dark:text-blue-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Mensajes Flash -->
                @if (session()->has('success'))
                    <div class="bg-green-100 dark:bg-green-900/40 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 rounded-xl shadow-md animate-fade-in-down">
                        <div class="font-bold text-lg">¡Acceso Permitido!</div>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="bg-red-100 dark:bg-red-900/40 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-xl shadow-md animate-bounce-short">
                        <div class="font-bold text-lg">¡Acceso Denegado!</div>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- MODAL DE CONFIRMACIÓN -->
        @if($mostrar_validacion && $ticket_seleccionado)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                <div class="bg-white dark:bg-primary-900 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-scale-up">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white text-center">
                        <h3 class="text-2xl font-bold">Confirmar Acceso</h3>
                        <p class="opacity-90 mt-1">{{ $ticket_seleccionado->evento->nombre }}</p>
                    </div>
                    
                    <div class="p-6 text-center">
                        <div class="w-24 h-24 mx-auto bg-gray-200 dark:bg-primary-800 rounded-full mb-4 overflow-hidden">
                            <img src="{{ $ticket_seleccionado->user->profile_photo_url }}" alt="{{ $ticket_seleccionado->user->name }}" class="w-full h-full object-cover">
                        </div>
                        <h4 class="text-xl font-bold text-secondary-900 dark:text-white">{{ $ticket_seleccionado->user->name }}</h4>
                        <p class="text-secondary-500 dark:text-secondary-400 mb-4">{{ $ticket_seleccionado->user->email }}</p>
                        
                        <div class="bg-secondary-50 dark:bg-primary-800 p-3 rounded-xl mb-6 inline-block">
                            <span class="text-xs font-bold uppercase tracking-wider text-secondary-500">Folio Ticket</span>
                            <div class="text-lg font-mono font-bold text-secondary-800 dark:text-secondary-200">{{ $ticket_seleccionado->folio }}</div>
                        </div>

                        <div class="flex gap-3 justify-center">
                            <button wire:click="cerrarModal" class="px-6 py-3 bg-white dark:bg-primary-800 border border-secondary-300 dark:border-primary-600 text-secondary-700 dark:text-secondary-300 font-bold rounded-xl hover:bg-secondary-50 dark:hover:bg-primary-700 transition">
                                Cancelar
                            </button>
                            <button wire:click="validarTicket({{ $ticket_seleccionado->id }}, {{ auth()->id() }})" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg transform hover:scale-105 transition">
                                ✅ Validar Entrada
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let html5QrcodeScanner = null;

        function startScanner() {
            // Ocultar botón de inicio
            document.getElementById('start-camera-btn').style.display = 'none';
            document.getElementById('camera-status').innerText = 'ACTIVO';
            document.getElementById('camera-status').className = 'text-xs font-bold text-green-500 uppercase tracking-wide animate-pulse';

            html5QrcodeScanner = new Html5Qrcode("reader");
            
            const config = { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            };
            
            html5QrcodeScanner.start(
                { facingMode: "environment" }, 
                config, 
                onScanSuccess, 
                onScanFailure
            ).catch(err => {
                console.error("Error starting scanner", err);
                alert("No se pudo acceder a la cámara. Asegúrate de dar permisos.");
                document.getElementById('start-camera-btn').style.display = 'flex';
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Code matched = ${decodedText}`, decodedResult);
            
            // Reproducir sonido de "beep"
            let beep = new Audio('https://assets.mixkit.co/sfx/preview/mixkit-software-interface-start-2574.mp3'); 
            beep.play().catch(e => console.log('Audio play failed', e));

            // Pausar temporalmente para evitar lecturas dobles
            if (html5QrcodeScanner) {
                html5QrcodeScanner.pause();
            }

            // Enviar al componente Livewire (buscamos en el texto el token o lo que sea)
            // Asumimos que el QR contiene el token directamente o una URL
            // Si es URL, extraemos el token, si es texto plano, lo usamos
            
            @this.set('busqueda', decodedText);
            @this.call('buscarTicket');

            // Después de procesar (simulado por timeout), reanudar
            setTimeout(() => {
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.resume();
                }
            }, 2000);
        }

        function onScanFailure(error) {
            // No hacer nada, es ruidoso
        }

        // Si el usuario quiere iniciar automáticamente
        document.addEventListener('livewire:initialized', () => {
             // Opcional: startScanner(); si queremos auto-inicio sin click
        });
        
        // Escuchar evento de validado para reiniciar
        Livewire.on('ticketValidado', () => {
            // Feedback visual extra si se quiere
        });
    </script>
    
    <style>
        .animate-scan {
            animation: scan-vertical 2s linear infinite;
        }
        @keyframes scan-vertical {
            0% { top: 0%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
    </style>
</div>
