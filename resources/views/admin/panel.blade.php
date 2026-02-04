<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-secondary-800 dark:text-white leading-tight">
            Panel de Administración
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Cabecera Estilo Staff -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="relative w-16 h-16 bg-gradient-to-br from-cyan-400 via-blue-500 to-blue-700 rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:rotate-3 transition-all duration-300">
                        <div class="absolute inset-0 bg-white/20 rounded-3xl backdrop-blur-sm"></div>
                        <div class="relative z-10">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-600 to-blue-700 bg-clip-text text-transparent">Gestión de Eventos</h1>
                        <p class="text-secondary-600 dark:text-secondary-400 mt-2">Administra los eventos y sus aforos</p>
                    </div>
                </div>

                <a href="{{ route('eventos.create') }}" class="w-full md:w-auto bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-cyan-700 shadow-lg transform hover:scale-105 transition font-bold flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nuevo Evento
                </a>
            </div>

            <!-- VISTA DE ESCRITORIO (Tabla Estilo Dashboard) -->
            <div class="hidden md:block bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 p-1">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-secondary-200 dark:border-primary-800">
                            <th class="px-6 py-4 text-left text-xs font-medium text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Evento</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Aforo</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-secondary-600 dark:text-secondary-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary-100 dark:divide-primary-800">
                        @forelse ($eventos as $evento)
                        <tr class="hover:bg-secondary-50 dark:hover:bg-primary-800/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($evento->imagen)
                                        <div class="flex-shrink-0 h-10 w-10 mr-4">
                                            <img class="h-10 w-10 rounded-full object-cover shadow-sm ring-2 ring-white dark:ring-primary-700" src="{{ Storage::url($evento->imagen) }}" alt="{{ $evento->nombre }}">
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-10 w-10 mr-4 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold shadow-sm">
                                            {{ substr($evento->nombre, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-bold text-secondary-900 dark:text-white">{{ $evento->nombre }}</div>
                                        <div class="text-xs text-secondary-500 dark:text-secondary-400">{{ Str::limit($evento->lugar, 30) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-600 dark:text-secondary-300">
                                {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                @php
                                    $ocupacion = $evento->ocupacion;
                                    $porcentaje = $evento->aforo_maximo > 0 ? ($ocupacion / $evento->aforo_maximo) * 100 : 0;
                                    $color = $porcentaje >= 90 ? 'bg-red-500' : ($porcentaje >= 70 ? 'bg-yellow-500' : 'bg-green-500');
                                @endphp
                                <div class="w-full max-w-xs mx-auto">
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="font-semibold text-secondary-700 dark:text-secondary-300">{{ $ocupacion }} / {{ $evento->aforo_maximo }}</span>
                                        <span class="text-secondary-500 dark:text-secondary-500">{{ round($porcentaje) }}%</span>
                                    </div>
                                    <div class="w-full bg-secondary-200 dark:bg-primary-800 rounded-full h-1.5 overflow-hidden">
                                        <div class="h-1.5 rounded-full {{ $color }} transition-all duration-500" style="width: {{ $porcentaje }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('eventos.users', $evento) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mr-4 font-bold inline-flex items-center group" title="Ver Inscritos">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </a>
                                <a href="{{ route('eventos.edit', $evento) }}" class="text-secondary-600 dark:text-secondary-400 hover:text-blue-600 dark:hover:text-blue-400 mr-4 font-bold transition-colors">
                                    Editar
                                </a>
                                <form action="{{ route('eventos.destroy', $evento) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que quieres eliminar el evento {{ $evento->nombre }}? Esta acción no se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-bold transition-colors">
                                        Borrar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-secondary-500 dark:text-secondary-400">
                                No hay eventos creados todavía.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-secondary-200 dark:border-primary-800">
                    {{ $eventos->links() }}
                </div>
            </div>

            <!-- VISTA DE MÓVIL (Tarjetas Modernas) -->
            <div class="md:hidden space-y-4">
                @forelse ($eventos as $evento)
                    <div class="bg-white dark:bg-primary-900 rounded-xl shadow-md p-5 border border-secondary-200 dark:border-primary-800">
                        
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex gap-3">
                                @if($evento->imagen)
                                    <img class="h-12 w-12 rounded-lg object-cover shadow-sm bg-secondary-100" src="{{ Storage::url($evento->imagen) }}" alt="{{ $evento->nombre }}">
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                        {{ substr($evento->nombre, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-bold text-secondary-900 dark:text-white text-lg leading-tight">{{ $evento->nombre }}</h3>
                                    <p class="text-xs text-secondary-500 dark:text-secondary-400 mt-1 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ Str::limit($evento->lugar, 25) }}
                                    </p>
                                </div>
                            </div>
                            <!-- Aforo Badge + Progress -->
                            <div class="flex flex-col items-end min-w-[80px]">
                                @php
                                    $ocup = $evento->ocupacion;
                                    $max = $evento->aforo_maximo;
                                    $porc = $max > 0 ? ($ocup / $max) * 100 : 0;
                                    $col = $porc >= 90 ? 'bg-red-500' : ($porc >= 70 ? 'bg-yellow-500' : 'bg-green-500');
                                @endphp
                                <span class="text-xs font-bold text-secondary-700 dark:text-secondary-300 mb-1">
                                    {{ $ocup }}/{{ $max }}
                                </span>
                                <div class="w-20 bg-secondary-200 dark:bg-primary-800 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-1.5 rounded-full {{ $col }}" style="width: {{ $porc }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center text-sm text-secondary-600 dark:text-secondary-300 mb-4 bg-secondary-50 dark:bg-primary-800/50 p-2 rounded-lg">
                            <svg class="w-4 h-4 mr-2 text-secondary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($evento->fecha)->translatedFormat('d F, Y - H:i') }}
                        </div>

                        <div class="pt-2 border-t border-secondary-100 dark:border-primary-800">
                            <a href="{{ route('eventos.users', $evento) }}" class="mb-3 flex justify-center items-center w-full py-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg text-sm font-semibold text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                Ver Inscritos ({{ $evento->ocupacion }})
                            </a>
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('eventos.edit', $evento) }}" class="text-center w-full py-2 bg-white dark:bg-primary-800 border border-secondary-200 dark:border-primary-700 rounded-lg text-sm font-semibold text-secondary-700 dark:text-secondary-200 hover:bg-secondary-50 dark:hover:bg-primary-700 transition">
                                    Editar
                                </a>
                                <form action="{{ route('eventos.destroy', $evento) }}" method="POST" onsubmit="return confirm('¿Eliminar?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full py-2 bg-white dark:bg-primary-800 border border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 rounded-lg text-sm font-semibold hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center p-8 bg-white dark:bg-primary-900 rounded-lg border border-dashed border-secondary-300 dark:border-primary-700">
                        <p class="text-secondary-500 dark:text-secondary-400">No hay eventos visibles.</p>
                    </div>
                @endforelse
                
                <div class="mt-4">
                    {{ $eventos->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>