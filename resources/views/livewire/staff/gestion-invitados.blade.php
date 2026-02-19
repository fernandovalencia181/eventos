<div>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-secondary-900">
            🌟 Invitados Especiales
        </h1>
        <button 
            wire:click="$set('mostrarFormulario', {{ !$mostrarFormulario }})"
            class="px-4 py-2 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 font-medium transition-colors"
        >
            @if($mostrarFormulario) ❌ Cancelar @else ➕ Agregar Invitado @endif
        </button>
    </div>

    <!-- Mensajes Flash -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulario Nuevo Invitado -->
    @if($mostrarFormulario)
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-secondary-100">
            <h2 class="text-xl font-semibold text-secondary-900 mb-4">
                Registrar Nuevo Invitado
            </h2>
            
            <form wire:submit.prevent="guardarInvitado" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nombre -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">
                            Nombre Completo *
                        </label>
                        <input 
                            type="text" 
                            wire:model="nombre"
                            class="w-full px-4 py-2 border border-secondary-300 rounded-xl"
                            placeholder="Juan Pérez García"
                        >
                        @error('nombre') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">
                            Tipo de Invitado *
                        </label>
                        <select wire:model="tipo" class="w-full px-4 py-2 border border-secondary-300 rounded-xl">
                            <option value="ponente">Ponente</option>
                            <option value="prensa">Prensa</option>
                            <option value="vip">VIP</option>
                            <option value="organizador">Organizador</option>
                        </select>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">
                            Email (opcional)
                        </label>
                        <input 
                            type="email" 
                            wire:model="email"
                            class="w-full px-4 py-2 border border-secondary-300 rounded-xl"
                            placeholder="invitado@ejemplo.com"
                        >
                        @error('email') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">
                            Teléfono (opcional)
                        </label>
                        <input 
                            type="text" 
                            wire:model="telefono"
                            class="w-full px-4 py-2 border border-secondary-300 rounded-xl"
                            placeholder="+52 999 999 9999"
                        >
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex gap-4">
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-3 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 font-medium"
                    >
                        💾 Guardar Invitado
                    </button>
                    <button 
                        type="button"
                        wire:click="$set('mostrarFormulario', false)"
                        class="px-6 py-3 bg-secondary-600 text-white rounded-xl hover:bg-secondary-700 font-medium"
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
            <div class="bg-white rounded-xl shadow-lg p-6 @if($invitado->ha_ingresado) border-l-4 border-green-500 @endif">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 text-white flex items-center justify-center text-2xl font-bold">
                        {{ substr($invitado->nombre, 0, 1) }}
                    </div>
                    
                    @if($invitado->ha_ingresado)
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                            ✅ INGRESÓ
                        </span>
                    @else
                        <span class="px-3 py-1 bg-secondary-100 text-secondary-800 rounded-full text-xs font-bold">
                            ⏳ PENDIENTE
                        </span>
                    @endif
                </div>

                <h3 class="text-lg font-bold text-secondary-900 mb-2">
                    {{ $invitado->nombre }}
                </h3>

                <div class="space-y-2 mb-4">
                    <p class="text-sm text-secondary-600">
                        <span class="font-medium">Tipo:</span> 
                        <span class="px-2 py-1 bg-yellow-100 rounded text-xs">
                            {{ ucfirst($invitado->tipo) }}
                        </span>
                    </p>
                    
                    @if($invitado->email)
                        <p class="text-sm text-secondary-600">
                            📧 {{ $invitado->email }}
                        </p>
                    @endif
                    
                    @if($invitado->telefono)
                        <p class="text-sm text-secondary-600">
                            📞 {{ $invitado->telefono }}
                        </p>
                    @endif

                    <p class="text-sm font-mono text-secondary-900 font-bold">
                        🎫 {{ $invitado->folio }}
                    </p>

                    @if($invitado->ha_ingresado)
                        <p class="text-sm text-green-600">
                            ✅ Ingresó: {{ $invitado->hora_ingreso->format('d/m/Y H:i') }}
                        </p>
                    @endif
                </div>

                <!-- QR Code -->
                @if($invitado->qr_code)
                    <div class="bg-secondary-50 rounded-xl p-4 mb-4 text-center">
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
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm font-medium"
                        >
                            🗑️ Eliminar
                        </button>
                    @endif
                    
                    @if($invitado->qr_code)
                        <a 
                            href="{{ Storage::url($invitado->qr_code) }}" 
                            download
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 text-sm font-medium text-center"
                        >
                            📥 Descargar QR
                        </a>
                    @endif
                </div>

                <p class="text-xs text-secondary-500 mt-4">
                    Registrado por: {{ $invitado->registrador->name }}
                </p>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl shadow-lg p-12 text-center">
                <p class="text-secondary-500 text-lg">
                    No hay invitados especiales registrados
                </p>
            </div>
        @endforelse
    </div>
</div>
