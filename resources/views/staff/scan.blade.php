<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            Escáner: {{ isset($evento) ? $evento->nombre : 'Seleccionar Evento' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-primary-900 border border-secondary-200 dark:border-primary-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                @if(isset($evento))
                    <!-- VISTA DE CÁMARA DIRECTA CUSTOM -->
                    <div class="text-center mb-6">
                        <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-800 text-sm font-bold px-3 py-1 rounded-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Evento: {{ $evento->nombre }}
                        </span>
                    </div>

                    <!-- Contenedor de Cámara -->
                    <div class="relative bg-black rounded-2xl overflow-hidden shadow-xl" style="max-width: 500px; margin: 0 auto; min-height: 400px;">
                        <div id="reader" class="w-full h-full"></div>
                        
                        <!-- Overlay Inicial / Botón de Inicio -->
                        <div id="camera-overlay" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900 z-20 cursor-pointer" onclick="startScanner()">
                            <div class="w-20 h-20 rounded-full bg-green-600 flex items-center justify-center mb-4 shadow-lg shadow-green-500/30 animate-pulse">
                                <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white">Tocar para Iniciar</h3>
                            <p class="text-gray-400 text-sm mt-1">Activa la cámara para escanear</p>
                        </div>

                        <!-- Estado mientras escanea -->
                        <div id="scanning-overlay" class="absolute inset-0 pointer-events-none hidden z-10">
                            <div class="absolute inset-0 border-2 border-green-500/50 m-8 rounded-lg flex items-center justify-center">
                                <div class="w-full h-0.5 bg-green-500/80 animate-scan"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="result-container" class="mt-6 p-4 rounded-xl hidden text-center transition-all"></div>

                    <!-- Scripts de html5-qrcode -->
                    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
                    <script>
                        let html5Qrcode = null;
                        let isScanning = false;

                        function startScanner() {
                            document.getElementById('camera-overlay').style.display = 'none';
                            document.getElementById('scanning-overlay').classList.remove('hidden');

                            html5Qrcode = new Html5Qrcode("reader");
                            
                            const config = { 
                                fps: 10, 
                                qrbox: { width: 250, height: 250 },
                                aspectRatio: 1.0
                            };
                            
                            html5Qrcode.start(
                                { facingMode: "environment" }, 
                                config, 
                                onScanSuccess, 
                                onScanFailure
                            ).catch(err => {
                                console.error("Error starting scanner", err);
                                alert("No se pudo acceder a la cámara. Revisa los permisos.");
                                document.getElementById('camera-overlay').style.display = 'flex';
                            });
                            
                            isScanning = true;
                        }

                        function onScanSuccess(decodedText, decodedResult) {
                            if (!isScanning) return;
                            
                            console.log(`Code matched = ${decodedText}`, decodedResult);
                            
                            // Feedback sonoro
                            try {
                                let beep = new Audio('https://assets.mixkit.co/sfx/preview/mixkit-software-interface-start-2574.mp3'); 
                                beep.play();
                            } catch(e) {}

                            // Pausar temporalmente
                            html5Qrcode.pause();
                            isScanning = false;
                            
                            // Mostrar procesando
                            showResult('Procesando...', 'blue');

                            // Enviar al backend
                            fetch("{{ route('staff.validar') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    codigo: decodedText, // Asegurando usar 'codigo' que es lo que espera el controlador
                                    evento_id: {{ $evento->id }}
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if(data.success) {
                                    showResult(`✅ ${data.mensaje}`, 'green');
                                } else {
                                    showResult(`❌ ${data.mensaje}`, 'red');
                                }
                                
                                // Reanudar tras 2.5s
                                setTimeout(() => {
                                    hideResult();
                                    html5Qrcode.resume();
                                    isScanning = true;
                                }, 2500);
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showResult('⚠️ Error de conexión', 'red');
                                setTimeout(() => {
                                    hideResult();
                                    html5Qrcode.resume();
                                    isScanning = true;
                                }, 2500);
                            });
                        }

                        function onScanFailure(error) {
                            // Ignorar errores de frame vacío
                        }

                        function showResult(text, color) {
                            const container = document.getElementById('result-container');
                            const colors = {
                                'green': 'bg-green-100 text-green-800 border-green-200',
                                'red': 'bg-red-100 text-red-800 border-red-200',
                                'blue': 'bg-blue-100 text-blue-800 border-blue-200'
                            };
                            
                            container.className = `mt-6 p-4 rounded-xl border ${colors[color]} font-bold text-lg animate-fade-in-down`;
                            container.innerHTML = text;
                            container.classList.remove('hidden');
                        }

                        function hideResult() {
                            document.getElementById('result-container').classList.add('hidden');
                        }
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

                @else
                    <!-- Si por alguna razón no hay evento, mostrar selector simple -->
                    <p class="text-center text-red-500">No se ha seleccionado un evento válido o no tienes uno asignado.</p>
                    <div class="mt-4 text-center">
                        <a href="{{ route('staff.index') }}" class="text-blue-600 hover:underline">Volver al panel</a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
