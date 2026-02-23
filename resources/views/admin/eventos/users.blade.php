<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header con Estilo Unificado -->
            <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <div class="relative w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-lg transform hover:scale-105 transition-all duration-300 flex-shrink-0">
                        <div class="absolute inset-0 bg-white/20 rounded-2xl backdrop-blur-sm"></div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-white relative z-10">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">
                            {{ $evento->nombre }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mt-0.5">
                            Gestión de inscripciones • <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $evento->ocupacion }} asistentes</span>
                        </p>
                    </div>
                </div>

                <a href="{{ route('admin.dashboard') }}" 
                   class="w-full md:w-auto justify-center bg-white dark:bg-primary-900 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-primary-700 px-5 py-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-primary-800 transition shadow-sm inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    Volver al Panel
                </a>
            </div>

            <!-- FILTROS Y BÚSQUEDA -->
            <div class="bg-white dark:bg-primary-900 rounded-lg shadow-md p-6 border border-secondary-200 dark:border-primary-800 mb-8">
                <form method="GET" action="{{ route('eventos.users', $evento) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    
                    <!-- Buscador -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar Inscrito</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, email, teléfono..." 
                                class="w-full rounded-lg border-gray-300 dark:border-primary-600 bg-white dark:bg-primary-800 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 pl-10 h-10">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Filtro Ciclos -->
                    <div>
                        <label for="course" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filtrar por Ciclo</label>
                        <select name="course" class="w-full rounded-lg border-gray-300 dark:border-primary-600 bg-white dark:bg-primary-800 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 h-10">
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
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition shadow-md h-10 flex items-center justify-center">
                            Filtrar
                        </button>
                        @if(request()->has('search') || request()->has('course'))
                            <a href="{{ route('eventos.users', $evento) }}" class="px-3 bg-gray-100 hover:bg-gray-200 dark:bg-primary-800 dark:hover:bg-primary-700 text-gray-600 dark:text-gray-300 rounded-lg border border-gray-300 dark:border-primary-600 transition h-10 flex items-center justify-center" title="Limpiar filtros">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- VISTA ESCRITORIO (Tabla Estilizada) -->
            <div class="hidden md:block bg-white dark:bg-primary-900 rounded-lg shadow-md border border-secondary-200 dark:border-primary-800 overflow-hidden mb-8">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-primary-800">
                        <thead class="bg-gray-50 dark:bg-primary-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Titular</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email / Teléfono</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acompañantes</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Registro</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ciclo</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-primary-900 divide-y divide-gray-200 dark:divide-primary-800">
                            @forelse ($registrations as $reg)
                            <tr class="hover:bg-gray-50 dark:hover:bg-primary-800/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-cyan-500 flex items-center justify-center text-white font-bold">
                                                {{ substr($reg->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $reg->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white flex items-center gap-1">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ $reg->email }}
                                    </div>
                                    @if(optional($reg->user)->phone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $reg->user->phone) }}" target="_blank" class="mt-1 flex items-center text-sm text-green-600 dark:text-green-400 font-medium hover:text-green-700 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                            {{ $reg->user->phone }}
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($reg->guests->count() > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400 mb-1">
                                            +{{ $reg->guests->count() }} Acompañantes
                                        </span>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 pl-1 border-l-2 border-gray-200 dark:border-gray-700 overflow-hidden">
                                            @foreach($reg->guests as $guest)
                                                <div class="truncate w-32" title="{{ $guest->name }}">{{ $guest->name }}</div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Sin acompañantes</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $reg->created_at->format('d/m/Y') }} <br>
                                    <span class="text-xs">{{ $reg->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ Str::limit($reg->course, 20) ?? '-' }}
                                    @if($reg->course && strlen($reg->course) > 20)
                                        <span title="{{ $reg->course }}" class="cursor-help text-gray-400">...</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        <p>No se encontraron inscritos coincidiendo con la búsqueda.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination unified style with Invitados view -->
                <div class="px-6 py-4 border-t border-secondary-200 dark:border-primary-800 bg-gray-50 dark:bg-primary-800/50">
                    {{ $registrations->links() }}
                </div>
            </div>

            <!-- VISTA MÓVIL (Tarjetas Estilizadas) -->
            <div class="md:hidden space-y-4">
                @forelse ($registrations as $reg)
                    <div class="bg-white dark:bg-primary-900 rounded-xl shadow-md p-5 border border-secondary-200 dark:border-primary-800">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-cyan-500 flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($reg->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white leading-tight">{{ $reg->name }}</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        {{ $reg->email }}
                                    </p>
                                </div>
                            </div>
                            <span class="text-xs bg-gray-100 dark:bg-primary-800 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">
                                {{ $reg->created_at->format('d/m') }}
                            </span>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                             <!-- Ciclo Display -->
                            <div class="text-sm text-secondary-600 dark:text-secondary-300 bg-gray-50 dark:bg-primary-800/50 p-2.5 rounded-lg border border-gray-100 dark:border-primary-700">
                                <span class="font-semibold text-xs uppercase tracking-wide text-gray-400 block mb-1">Ciclo</span> 
                                {{ $reg->course ?? 'No especificado' }}
                            </div>

                            @if(optional($reg->user)->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $reg->user->phone) }}" target="_blank" class="flex items-center justify-center w-full py-2 bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 rounded-lg text-sm font-semibold text-green-700 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/40 transition gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                    {{ $reg->user->phone }}
                                </a>
                            @endif
                        </div>

                        @if($reg->guests->count() > 0)
                            <div class="mt-3">
                                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    Acompañantes (+{{ $reg->guests->count() }})
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($reg->guests as $guest)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-100 dark:bg-green-900/20 dark:text-green-400 dark:border-green-900/50">
                                            {{ $guest->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center p-8 bg-white dark:bg-primary-900 rounded-lg border border-dashed border-secondary-300 dark:border-primary-700 shadow-sm">
                        <p class="text-secondary-500 dark:text-secondary-400">No hay inscritos que mostrar.</p>
                    </div>
                @endforelse
                
                <div class="mt-4">
                    {{ $registrations->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>