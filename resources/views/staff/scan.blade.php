<x-app-layout>
    <!-- Header Integrado (eliminado, ahora está dentro del contenedor principal como en edit) -->

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Cabecera Estilo Staff (Copiada de edit.blade.php) -->
            <div class="mb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4 text-center sm:text-left">
                    <div class="relative w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:rotate-3 transition-all duration-300 flex-shrink-0">
                        <div class="absolute inset-0 bg-white/20 rounded-3xl backdrop-blur-sm"></div>
                        <div class="relative z-10">
                            <!-- Icono Scanner -->
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            Escáner QR
                        </h1>
                        <p class="text-secondary-600 dark:text-secondary-400 mt-2">
                           Validar acceso a: <span class="font-semibold">{{ isset($evento) ? $evento->nombre : 'Seleccionar Evento' }}</span>
                        </p>
                    </div>
                </div>

                <a href="{{ route('staff.index') }}" 
                   class="w-full sm:w-auto justify-center bg-white dark:bg-primary-800 text-secondary-700 dark:text-secondary-300 border border-secondary-300 dark:border-primary-600 px-5 py-2.5 rounded-xl hover:bg-secondary-50 dark:hover:bg-primary-700 transition shadow-sm inline-flex items-center gap-2 font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    Volver al Panel
                </a>
            </div>

            <div class="bg-white dark:bg-primary-900 overflow-hidden shadow-xl rounded-xl border border-secondary-200 dark:border-primary-800 p-6 sm:p-8">
                
                @if(isset($evento))
                    <!-- VISTA DE CÁMARA DIRECTA CUSTOM -->
                    <!-- <div class="text-center mb-6">
                        <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-800 text-sm font-bold px-3 py-1 rounded-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Evento: {{ $evento->nombre }}
                        </span>
                    </div> -->

                    <!-- Contenedor de Cámara -->
                    <div class="relative bg-black rounded-2xl overflow-hidden shadow-xl" style="max-width: 500px; margin: 0 auto; min-height: 400px;">
                        <div id="reader" class="w-full h-full"></div>
                        
                        <!-- Overlay Inicial / Botón de Inicio -->
                        <div id="camera-overlay" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900 z-20 cursor-pointer" onclick="startScanner()">
                            <div class="w-20 h-20 rounded-full bg-blue-600 flex items-center justify-center mb-4 shadow-lg shadow-blue-500/30 animate-pulse">
                                <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white">Tocar para Iniciar</h3>

                            <p class="text-gray-400 text-sm mt-1">Activa la cámara para escanear</p>
                        </div>

                        <!-- Estado mientras escanea -->
                        <div id="scanning-overlay" class="absolute inset-0 pointer-events-none hidden z-10">
                            <div class="absolute inset-0 border-2 border-blue-500/50 m-8 rounded-lg flex items-center justify-center">
                                <div class="w-full h-0.5 bg-blue-500/80 animate-scan"></div>
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
                            showResult('Procesandooo...', 'blue');

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
                            const types = {
                                'green': {
                                    classes: 'bg-green-100 dark:bg-green-900/30 border-green-400 dark:border-green-700 text-green-700 dark:text-green-400',
                                    closeBtn: 'text-green-700 dark:text-green-400 hover:text-green-900'
                                },
                                'red': {
                                    classes: 'bg-red-100 dark:bg-red-900/30 border-red-400 dark:border-red-700 text-red-700 dark:text-red-400',
                                    closeBtn: 'text-red-700 dark:text-red-400 hover:text-red-900'
                                },
                                'blue': {
                                    classes: 'bg-blue-100 dark:bg-blue-900/30 border-blue-400 dark:border-blue-700 text-blue-700 dark:text-blue-400',
                                    closeBtn: 'text-blue-700 dark:text-blue-400 hover:text-blue-900'
                                }
                            };
                            
                            const type = types[color] || types['blue'];
                            
                            container.className = `mt-6 mb-4 flex justify-between items-center px-4 py-3 rounded-lg border ${type.classes} animate-fade-in-down`;
                            
                            container.innerHTML = `
                                <span>${text}</span>
                                <button onclick="hideResult()" type="button" class="${type.closeBtn} font-bold text-xl leading-none">&times;</button>
                            `;
                            
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
