<div class="max-w-7xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        
        <!-- Sidebar -->
        <aside class="py-6 lg:col-span-3">
            <nav class="flex space-x-2 lg:flex-col lg:space-x-0 lg:space-y-1 overflow-x-auto pb-4 lg:pb-0">
                @php
                    $navLinks = [
                        ['route' => 'profile.edit', 'label' => 'Perfil', 'icon' => 'M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z'],
                        ['route' => 'user-password.edit', 'label' => 'Contraseña', 'icon' => 'M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z'],
                    ];
                @endphp

                @foreach ($navLinks as $link)
                    @php $isActive = request()->routeIs($link['route']); @endphp
                    <a href="{{ route($link['route']) }}" wire:navigate
                       class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors whitespace-nowrap
                              {{ $isActive 
                                 ? 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100 hover:text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300' 
                                 : 'text-gray-900 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-primary-800' }}">
                        <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ $isActive ? 'text-indigo-700 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-400' }}" 
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}" />
                        </svg>
                        <span class="truncate">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </aside>

        <!-- Content -->
        <div class="lg:col-span-9">
            
            <div class="mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4 w-full sm:w-auto">
                   <div class="relative w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">{{ $heading ?? 'Configuración' }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subheading ?? 'Gestiona tu cuenta y preferencias' }}</p>
                    </div>
                </div>

                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-4 py-2 bg-white dark:bg-primary-800 border border-secondary-200 dark:border-primary-700 text-secondary-600 dark:text-secondary-300 font-bold rounded-lg hover:bg-secondary-50 dark:hover:bg-primary-700 transition flex items-center justify-center gap-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    Volver al Panel
                </a>
            </div>
            
            <div class="bg-white dark:bg-primary-900 border border-secondary-200 dark:border-primary-800 shadow-xl rounded-2xl overflow-hidden">
                <div class="px-6 py-8 sm:p-10">
                    {{ $slot }}
                </div>
            </div>
        </div>

    </div>
</div>
