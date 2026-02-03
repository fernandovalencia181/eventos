<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Inscritos en: {{ $evento->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-4 flex justify-between items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:text-indigo-900 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al Panel
                </a>
                <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-indigo-400">
                    Total: {{ $evento->ocupacion }} asistentes
                </span>
            </div>

            <!-- FILTROS Y BÚSQUEDA -->
            <div class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <form method="GET" action="{{ route('eventos.users', $evento) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    
                    <!-- Buscador -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-xs font-bold text-gray-500 uppercase mb-1">Buscar</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, email, teléfono o invitado..." class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro Ciclos -->
                    <div>
                        <label for="course" class="block text-xs font-bold text-gray-500 uppercase mb-1">Filtrar por Ciclo</label>
                        <select name="course" class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Todos los ciclos</option>
                            @foreach($cursos as $curso)
                                <option value="{{ $curso }}" {{ request('course') == $curso ? 'selected' : '' }}>
                                    {{ Str::limit($curso, 30) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition shadow-sm">
                            Filtrar
                        </button>
                        @if(request()->has('search') || request()->has('course'))
                            <a href="{{ route('eventos.users', $evento) }}" class="flex items-center justify-center p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg border border-gray-300 transition" title="Limpiar filtros">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- VISTA ESCRITORIO -->
            <div class="hidden md:block bg-white overflow-hidden shadow-xl sm:rounded-lg border border-secondary-200">
                <table class="min-w-full divide-y divide-secondary-200">
                    <thead class="bg-secondary-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Titular</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Acompañantes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Fecha Registro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">Estudios</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-secondary-200">
                        @forelse ($registrations as $reg)
                        <tr class="hover:bg-secondary-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $reg->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $reg->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($reg->guests->count() > 0)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        +{{ $reg->guests->count() }}
                                    </span>
                                    <div class="text-xs text-gray-400 mt-1">
                                        @foreach($reg->guests as $guest)
                                            - {{ $guest->name }}<br>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">Sin acompañantes</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $reg->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $reg->course ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No hay inscritos en este evento.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-4">
                    {{ $registrations->links() }}
                </div>
            </div>

            <!-- VISTA MÓVIL -->
            <div class="md:hidden space-y-4">
                @forelse ($registrations as $reg)
                    <div class="bg-white rounded-xl shadow-md p-5 border border-secondary-100">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-bold text-secondary-900">{{ $reg->name }}</h3>
                                <p class="text-xs text-secondary-500">{{ $reg->email }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $reg->created_at->format('d/m') }}</span>
                        </div>
                        
                        <div class="text-sm text-gray-600 mb-2">
                            <span class="font-semibold text-xs uppercase tracking-wide text-gray-400">Estudios:</span> 
                            {{ $reg->course ?? 'N/A' }}
                        </div>

                        @if($reg->guests->count() > 0)
                            <div class="mt-3 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <p class="text-xs font-bold text-gray-500 mb-1">Acompañantes (+{{ $reg->guests->count() }}):</p>
                                <ul class="list-disc list-inside text-sm text-gray-700">
                                    @foreach($reg->guests as $guest)
                                        <li>{{ $guest->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center p-8 bg-white rounded-lg border border-dashed border-secondary-300">
                        <p class="text-secondary-500">No hay inscritos.</p>
                    </div>
                @endforelse
                
                <div class="mt-4">
                    {{ $registrations->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>