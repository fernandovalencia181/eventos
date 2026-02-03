<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel de Administración
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex justify-end mb-4">
                <a href="{{ route('eventos.create') }}" class="w-full sm:w-auto text-center bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 shadow-md transition font-bold">
                    + Nuevo Evento
                </a>
            </div>

            <!-- VISTA DE ESCRITORIO (Tabla) -->
            <div class="hidden md:block bg-white overflow-hidden shadow-xl sm:rounded-lg border border-secondary-200">
                <table class="min-w-full divide-y divide-secondary-200">
                    <thead class="bg-secondary-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Evento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-secondary-500 uppercase tracking-wider">Aforo</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-secondary-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-secondary-200">
                        @forelse ($eventos as $evento)
                        <tr class="hover:bg-secondary-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($evento->imagen)
                                        <div class="flex-shrink-0 h-10 w-10 mr-4">
                                            <img class="h-10 w-10 rounded-full object-cover shadow-sm" src="{{ Storage::url($evento->imagen) }}" alt="{{ $evento->nombre }}">
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-10 w-10 mr-4 bg-primary-100 rounded-full flex items-center justify-center text-primary-500 font-bold">
                                            {{ substr($evento->nombre, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-bold text-secondary-900">{{ $evento->nombre }}</div>
                                        <div class="text-xs text-secondary-500">{{ Str::limit($evento->lugar, 30) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-600">
                                {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-600 text-center">
                                @php
                                    $ocupacion = $evento->ocupacion;
                                    $porcentaje = $evento->aforo_maximo > 0 ? ($ocupacion / $evento->aforo_maximo) * 100 : 0;
                                    $color = $porcentaje >= 90 ? 'bg-red-500' : ($porcentaje >= 70 ? 'bg-yellow-500' : 'bg-green-500');
                                @endphp
                                <div class="w-full max-w-xs mx-auto">
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="font-semibold">{{ $ocupacion }} / {{ $evento->aforo_maximo }}</span>
                                        <span class="text-gray-500">{{ round($porcentaje) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full {{ $color }}" style="width: {{ $porcentaje }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('eventos.users', $evento) }}" class="text-indigo-600 hover:text-indigo-900 mr-4 font-bold inline-flex items-center" title="Ver Inscritos">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </a>
                                <a href="{{ route('eventos.edit', $evento) }}" class="text-primary-600 hover:text-primary-900 mr-4 font-bold">
                                    Editar
                                </a>
                                <form action="{{ route('eventos.destroy', $evento) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que quieres eliminar el evento {{ $evento->nombre }}? Esta acción no se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold">
                                        Borrar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-secondary-500">
                                No hay eventos creados todavía.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- VISTA DE MÓVIL (Tarjetas) -->
            <div class="md:hidden space-y-4">
                @forelse ($eventos as $evento)
                    <div class="bg-white rounded-xl shadow-md p-5 border border-secondary-100">
                        
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex gap-3">
                                @if($evento->imagen)
                                    <img class="h-12 w-12 rounded-lg object-cover shadow-sm bg-gray-100" src="{{ Storage::url($evento->imagen) }}" alt="{{ $evento->nombre }}">
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-primary-100 flex items-center justify-center text-primary-600 font-bold text-lg">
                                        {{ substr($evento->nombre, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-bold text-secondary-900 text-lg leading-tight">{{ $evento->nombre }}</h3>
                                    <p class="text-xs text-secondary-500 mt-1 flex items-center">
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
                                <span class="text-xs font-bold text-gray-700 mb-1">
                                    {{ $ocup }}/{{ $max }}
                                </span>
                                <div class="w-20 bg-gray-200 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full {{ $col }}" style="width: {{ $porc }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center text-sm text-secondary-600 mb-4 bg-secondary-50 p-2 rounded-lg">
                            <svg class="w-4 h-4 mr-2 text-secondary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($evento->fecha)->translatedFormat('d F, Y - H:i') }}
                        </div>

                        <div class="pt-2 border-t border-secondary-100">
                            <a href="{{ route('eventos.users', $evento) }}" class="mb-3 flex justify-center items-center w-full py-2 bg-indigo-50 border border-indigo-200 rounded-lg text-sm font-semibold text-indigo-700 hover:bg-indigo-100 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                Ver Inscritos ({{ $evento->ocupacion }})
                            </a>
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('eventos.edit', $evento) }}" class="text-center w-full py-2 bg-white border border-secondary-300 rounded-lg text-sm font-semibold text-secondary-700 hover:bg-secondary-50 transition">
                                    Editar
                                </a>
                                <form action="{{ route('eventos.destroy', $evento) }}" method="POST" onsubmit="return confirm('¿Eliminar?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full py-2 bg-white border border-red-200 text-red-600 rounded-lg text-sm font-semibold hover:bg-red-50 transition">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center p-8 bg-white rounded-lg border border-dashed border-secondary-300">
                        <p class="text-secondary-500">No hay eventos visibles.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>