<div>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-secondary-900">
            Panel de Validación - Check-in
        </h1>
        @if($eventoId)
            <p class="text-secondary-500 mt-2">
                Evento: {{ $ticketEncontrado->evento->nombre ?? 'Cargando...' }}
            </p>
        @endif
    </div>

    <!-- Mensajes de alerta -->
    @if($mensaje)
        <div class="mb-6 p-4 rounded-xl @if($tipoMensaje === 'success') bg-green-50 border border-green-200 text-green-700 @elseif($tipoMensaje === 'error') bg-red-50 border border-red-200 text-red-700 @else bg-yellow-50 border border-yellow-200 text-yellow-700 @endif">
            {!! $mensaje !!}
        </div>
    @endif

    <!-- Escáner de QR/Folio -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-secondary-100">
        <h2 class="text-xl font-semibold text-secondary-900 mb-4">
            📱 Escanear Entrada
        </h2>
        
        <!-- Botón para activar cámara -->
        <div class="mb-4">
            <button 
                onclick="toggleCamera()"
                id="cameraBtn"
                class="w-full px-6 py-4 bg-primary-600 text-white rounded-xl hover:bg-primary-700 font-semibold text-lg shadow-md transition-all"
            >
                📷 Activar Cámara para Escanear QR
            </button>
        </div>

        <!-- Contenedor de la cámara (oculto por defecto) -->
        <div id="camera-container" class="hidden mb-4">
            <div class="relative bg-white rounded-xl overflow-hidden">
                <video id="qr-video" class="w-full" style="max-height: 400px; object-fit: cover;"></video>
                <div class="absolute inset-0 border-4 border-primary-500 pointer-events-none"></div>
                <button 
                    onclick="toggleCamera()"
                    class="absolute top-4 right-4 px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 font-medium"
                >
                    ✕ Cerrar Cámara
                </button>
            </div>
            <p class="text-center text-sm text-secondary-500 mt-2">
                Apunta la cámara al código QR del ticket
            </p>
        </div>
        
        <!-- Input manual -->
        <div class="flex gap-4">
            <input 
                type="text" 
                wire:model="folio"
                wire:keydown.enter="escanearFolio"
                id="folioInput"
                placeholder="O ingresa el folio manualmente (TKT-XXXXXXXX o INV-XXXXXXXX)"
                class="flex-1 px-4 py-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-lg"
            >
            <button 
                wire:click="escanearFolio"
                class="px-6 py-3 bg-secondary-900 text-white rounded-xl hover:bg-primary-600 font-medium transition-colors"
            >
                Validar
            </button>
        </div>
    </div>

    <!-- Script para el lector QR -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        let html5QrCode = null;
        let cameraActive = false;

        function toggleCamera() {
            const container = document.getElementById('camera-container');
            const btn = document.getElementById('cameraBtn');
            
            if (!cameraActive) {
                // Activar cámara
                container.classList.remove('hidden');
                btn.classList.add('hidden');
                startScanning();
            } else {
                // Desactivar cámara
                stopScanning();
                container.classList.add('hidden');
                btn.classList.remove('hidden');
            }
        }

        function startScanning() {
            html5QrCode = new Html5Qrcode("qr-video");
            
            html5QrCode.start(
                { facingMode: "environment" }, // Cámara trasera
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                (decodedText, decodedResult) => {
                    // QR escaneado exitosamente
                    console.log('QR Detectado:', decodedText);
                    
                    // Llenar el input con el folio
                    const inputElement = document.getElementById('folioInput');
                    inputElement.value = decodedText;
                    inputElement.dispatchEvent(new Event('input'));
                    
                    // Usar Livewire para actualizar y validar
                    window.Livewire.find('{{ $_instance->getId() }}').set('folio', decodedText).then(() => {
                        window.Livewire.find('{{ $_instance->getId() }}').call('escanearFolio');
                    });
                    
                    // Detener cámara
                    stopScanning();
                    document.getElementById('camera-container').classList.add('hidden');
                    document.getElementById('cameraBtn').classList.remove('hidden');
                },
                (errorMessage) => {
                    // Error de escaneo (normal cuando no detecta QR)
                }
            ).then(() => {
                cameraActive = true;
            }).catch((err) => {
                console.error('Error al iniciar cámara:', err);
                alert('No se pudo acceder a la cámara. Verifica los permisos del navegador.');
                document.getElementById('camera-container').classList.add('hidden');
                document.getElementById('cameraBtn').classList.remove('hidden');
            });
        }

        function stopScanning() {
            if (html5QrCode && cameraActive) {
                html5QrCode.stop().then(() => {
                    cameraActive = false;
                    html5QrCode = null;
                }).catch((err) => {
                    console.error('Error al detener cámara:', err);
                });
            }
        }

        // Limpiar al salir
        window.addEventListener('beforeunload', () => {
            if (cameraActive) {
                stopScanning();
            }
        });
    </script>

    <!-- Búsqueda Manual -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-secondary-100">
        <h2 class="text-xl font-semibold text-secondary-900 mb-4">
            🔍 Búsqueda Manual
        </h2>
        
        <div class="flex gap-4 mb-4">
            <input 
                type="text" 
                wire:model.live="busqueda"
                placeholder="Buscar por nombre o matrícula..."
                class="flex-1 px-4 py-3 border border-secondary-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            >
        </div>

        @if($resultado && count($resultado) > 0)
            <div class="border border-secondary-200 rounded-xl divide-y divide-secondary-200">
                @foreach($resultado as $usuario)
                    <div class="p-4 hover:bg-secondary-50 flex items-center justify-between transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center text-lg font-bold">
                                {{ substr($usuario->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-secondary-900">{{ $usuario->name }}</p>
                                <p class="text-sm text-secondary-500">{{ $usuario->email }}</p>
                            </div>
                        </div>
                        <button 
                            wire:click="seleccionarUsuario({{ $usuario->id }})"
                            class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 font-medium transition-colors"
                        >
                            Seleccionar
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Modal de Confirmación -->
    @if($mostrarModal)
        <div class="fixed inset-0 bg-white bg-opacity-50 flex items-center justify-center z-50" wire:click="cerrarModal">
            <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4 p-6 border border-secondary-100" wire:click.stop>
                <h3 class="text-2xl font-bold text-secondary-900 mb-4">
                    @if($tipoMensaje === 'success') ✅ @elseif($tipoMensaje === 'error') ❌ @else ⚠️ @endif
                    Información del Ticket
                </h3>

                @if($usuarioEncontrado)
                    <!-- Datos del Usuario -->
                    <div class="bg-secondary-50 rounded-xl p-6 mb-4 border border-secondary-100">
                        <div class="flex items-center space-x-6">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 text-white flex items-center justify-center text-3xl font-bold">
                                {{ substr($usuarioEncontrado->name, 0, 2) }}
                            </div>
                            <div class="flex-1">
                                <h4 class="text-2xl font-bold text-secondary-900 mb-2">
                                    {{ $usuarioEncontrado->name }}
                                </h4>
                                <p class="text-secondary-600">
                                    📧 {{ $usuarioEncontrado->email }}
                                </p>
                                @if($ticketEncontrado)
                                    <p class="text-secondary-600">
                                        🎫 Folio: <span class="font-mono font-bold">{{ $ticketEncontrado->folio }}</span>
                                    </p>
                                    <p class="text-secondary-600">
                                        📅 Compra: {{ $ticketEncontrado->fecha_compra->format('d/m/Y H:i') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if($invitadoEncontrado)
                    <!-- Datos del Invitado -->
                    <div class="bg-secondary-50 rounded-xl p-6 mb-4 border border-secondary-100">
                        <div class="flex items-center space-x-6">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-yellow-500 to-orange-600 text-white flex items-center justify-center text-3xl font-bold">
                                VIP
                            </div>
                            <div class="flex-1">
                                <h4 class="text-2xl font-bold text-secondary-900 mb-2">
                                    {{ $invitadoEncontrado->nombre }}
                                </h4>
                                <p class="text-secondary-600">
                                    🏷️ Tipo: <span class="font-bold">{{ ucfirst($invitadoEncontrado->tipo) }}</span>
                                </p>
                                @if($invitadoEncontrado->email)
                                    <p class="text-secondary-600">
                                        📧 {{ $invitadoEncontrado->email }}
                                    </p>
                                @endif
                                <p class="text-secondary-600">
                                    🎫 Folio: <span class="font-mono font-bold">{{ $invitadoEncontrado->folio }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Mensaje de estado -->
                <div class="mb-6 p-4 rounded-xl text-center text-lg font-medium @if($tipoMensaje === 'success') bg-green-50 text-green-800 border border-green-200 @elseif($tipoMensaje === 'error') bg-red-50 text-red-800 border border-red-200 @else bg-yellow-50 text-yellow-800 border border-yellow-200 @endif">
                    {!! $mensaje !!}
                </div>

                <!-- Botones -->
                <div class="flex gap-4">
                    @if($tipoMensaje === 'success')
                        <button 
                            wire:click="confirmarEntrada"
                            class="flex-1 px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 font-bold text-lg transition-colors"
                        >
                            ✅ Confirmar Entrada
                        </button>
                    @endif
                    <button 
                        wire:click="cerrarModal"
                        class="flex-1 px-6 py-3 bg-secondary-600 text-white rounded-xl hover:bg-secondary-700 font-bold text-lg transition-colors"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
