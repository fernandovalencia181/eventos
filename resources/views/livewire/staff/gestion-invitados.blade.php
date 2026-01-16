<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            🌟 Invitados Especiales
        </h1>
        <button 
            wire:click="$set('mostrarFormulario', {{ !$mostrarFormulario }})"
            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-medium"
        >
            @if($mostrarFormulario) ❌ Cancelar @else ➕ Agregar Invitado @endif
        </button>
    </div>

    <!-- Mensajes Flash -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulario Nuevo Invitado -->
    @if($mostrarFormulario)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                Registrar Nuevo Invitado
            </h2>
            
            <form wire:submit.prevent="guardarInvitado" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nombre -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nombre Completo *
                        </label>
                        <input 
                            type="text" 
                            wire:model="nombre"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            placeholder="Juan Pérez García"
                        >
                        @error('nombre') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tipo de Invitado *
                        </label>
                        <select wire:model="tipo" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="ponente">Ponente</option>
                            <option value="prensa">Prensa</option>
                            <option value="vip">VIP</option>
                            <option value="organizador">Organizador</option>
                        </select>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email (opcional)
                        </label>
                        <input 
                            type="email" 
                            wire:model="email"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            placeholder="invitado@ejemplo.com"
                        >
                        @error('email') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Teléfono (opcional)
                        </label>
                        <input 
                            type="text" 
                            wire:model="telefono"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                            placeholder="+52 999 999 9999"
                        >
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex gap-4">
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-medium"
                    >
                        💾 Guardar Invitado
                    </button>
                    <button 
                        type="button"
                        wire:click="$set('mostrarFormulario', false)"
                        class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium"
                    >
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Lista de Invitados -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($invitados as $invitado)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 @if($invitado->ha_ingresado) border-l-4 border-green-500 @endif">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 text-white flex items-center justify-center text-2xl font-bold">
                        {{ substr($invitado->nombre, 0, 1) }}
                    </div>
                    
                    @if($invitado->ha_ingresado)
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                            ✅ INGRESÓ
                        </span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-bold">
                            ⏳ PENDIENTE
                        </span>
                    @endif
                </div>

                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                    {{ $invitado->nombre }}
                </h3>

                <div class="space-y-2 mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium">Tipo:</span> 
                        <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900 rounded text-xs">
                            {{ ucfirst($invitado->tipo) }}
                        </span>
                    </p>
                    
                    @if($invitado->email)
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            📧 {{ $invitado->email }}
                        </p>
                    @endif
                    
                    @if($invitado->telefono)
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            📞 {{ $invitado->telefono }}
                        </p>
                    @endif

                    <p class="text-sm font-mono text-gray-900 dark:text-white font-bold">
                        🎫 {{ $invitado->folio }}
                    </p>

                    @if($invitado->ha_ingresado)
                        <p class="text-sm text-green-600 dark:text-green-400">
                            ✅ Ingresó: {{ $invitado->hora_ingreso->format('d/m/Y H:i') }}
                        </p>
                    @endif
                </div>

                <!-- QR Code -->
                @if($invitado->qr_code)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4 text-center">
                        <img 
                            src="{{ Storage::url($invitado->qr_code) }}" 
                            alt="QR Code" 
                            class="w-32 h-32 mx-auto"
                        >
                    </div>
                @endif

                <!-- Botones -->
                <div class="flex gap-2">
                    @if(!$invitado->ha_ingresado)
                        <button 
                            wire:click="eliminarInvitado({{ $invitado->id }})"
                            onclick="return confirm('¿Estás seguro de eliminar este invitado?')"
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium"
                        >
                            🗑️ Eliminar
                        </button>
                    @endif
                    
                    @if($invitado->qr_code)
                        <a 
                            href="{{ Storage::url($invitado->qr_code) }}" 
                            download
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium text-center"
                        >
                            📥 Descargar QR
                        </a>
                    @endif
                </div>

                <p class="text-xs text-gray-500 dark:text-gray-400 mt-4">
                    Registrado por: {{ $invitado->registrador->name }}
                </p>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 text-center">
                <p class="text-gray-500 dark:text-gray-400 text-lg">
                    No hay invitados especiales registrados
                </p>
            </div>
        @endforelse
    </div>
</div>
