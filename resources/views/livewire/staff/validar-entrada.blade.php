<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            Panel de Validación - Check-in
        </h1>
        @if($eventoId)
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                Evento: {{ $ticketEncontrado->evento->nombre ?? 'Cargando...' }}
            </p>
        @endif
    </div>

    <!-- Mensajes de alerta -->
    @if($mensaje)
        <div class="mb-6 p-4 rounded-lg @if($tipoMensaje === 'success') bg-green-100 border border-green-400 text-green-700 @elseif($tipoMensaje === 'error') bg-red-100 border border-red-400 text-red-700 @else bg-yellow-100 border border-yellow-400 text-yellow-700 @endif">
            {!! $mensaje !!}
        </div>
    @endif

    <!-- Escáner de QR/Folio -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
            📱 Escanear Entrada
        </h2>
        
        <div class="flex gap-4">
            <input 
                type="text" 
                wire:model="folio"
                wire:keydown.enter="escanearFolio"
                placeholder="Escanea el QR o ingresa el folio (TKT-XXXXXXXX o INV-XXXXXXXX)"
                class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-lg"
                autofocus
            >
            <button 
                wire:click="escanearFolio"
                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
            >
                Validar
            </button>
        </div>
    </div>

    <!-- Búsqueda Manual -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
            🔍 Búsqueda Manual
        </h2>
        
        <div class="flex gap-4 mb-4">
            <input 
                type="text" 
                wire:model.live="busqueda"
                placeholder="Buscar por nombre o matrícula..."
                class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
            >
        </div>

        @if($resultado && count($resultado) > 0)
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($resultado as $usuario)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-full bg-blue-500 text-white flex items-center justify-center text-lg font-bold">
                                {{ substr($usuario->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $usuario->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $usuario->email }}</p>
                            </div>
                        </div>
                        <button 
                            wire:click="seleccionarUsuario({{ $usuario->id }})"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
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
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="cerrarModal">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 p-6" wire:click.stop>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    @if($tipoMensaje === 'success') ✅ @elseif($tipoMensaje === 'error') ❌ @else ⚠️ @endif
                    Información del Ticket
                </h3>

                @if($usuarioEncontrado)
                    <!-- Datos del Usuario -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-4">
                        <div class="flex items-center space-x-6">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 text-white flex items-center justify-center text-3xl font-bold">
                                {{ substr($usuarioEncontrado->name, 0, 2) }}
                            </div>
                            <div class="flex-1">
                                <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                    {{ $usuarioEncontrado->name }}
                                </h4>
                                <p class="text-gray-600 dark:text-gray-300">
                                    📧 {{ $usuarioEncontrado->email }}
                                </p>
                                @if($ticketEncontrado)
                                    <p class="text-gray-600 dark:text-gray-300">
                                        🎫 Folio: <span class="font-mono font-bold">{{ $ticketEncontrado->folio }}</span>
                                    </p>
                                    <p class="text-gray-600 dark:text-gray-300">
                                        📅 Compra: {{ $ticketEncontrado->fecha_compra->format('d/m/Y H:i') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if($invitadoEncontrado)
                    <!-- Datos del Invitado -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-4">
                        <div class="flex items-center space-x-6">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-yellow-500 to-orange-600 text-white flex items-center justify-center text-3xl font-bold">
                                VIP
                            </div>
                            <div class="flex-1">
                                <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                    {{ $invitadoEncontrado->nombre }}
                                </h4>
                                <p class="text-gray-600 dark:text-gray-300">
                                    🏷️ Tipo: <span class="font-bold">{{ ucfirst($invitadoEncontrado->tipo) }}</span>
                                </p>
                                @if($invitadoEncontrado->email)
                                    <p class="text-gray-600 dark:text-gray-300">
                                        📧 {{ $invitadoEncontrado->email }}
                                    </p>
                                @endif
                                <p class="text-gray-600 dark:text-gray-300">
                                    🎫 Folio: <span class="font-mono font-bold">{{ $invitadoEncontrado->folio }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Mensaje de estado -->
                <div class="mb-6 p-4 rounded-lg text-center text-lg font-medium @if($tipoMensaje === 'success') bg-green-100 text-green-800 @elseif($tipoMensaje === 'error') bg-red-100 text-red-800 @else bg-yellow-100 text-yellow-800 @endif">
                    {!! $mensaje !!}
                </div>

                <!-- Botones -->
                <div class="flex gap-4">
                    @if($tipoMensaje === 'success')
                        <button 
                            wire:click="confirmarEntrada"
                            class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold text-lg"
                        >
                            ✅ Confirmar Entrada
                        </button>
                    @endif
                    <button 
                        wire:click="cerrarModal"
                        class="flex-1 px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-bold text-lg"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
