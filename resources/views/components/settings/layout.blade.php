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
                                 ? 'bg-primary-50 text-primary-700 hover:bg-primary-100 hover:text-primary-800' 
                                 : 'text-gray-900 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ $isActive ? 'text-primary-700' : 'text-gray-400 group-hover:text-gray-500' }}" 
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
            <div class="mb-4">
                <h2 class="text-2xl font-bold text-gray-900">{{ $heading ?? '' }}</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $subheading ?? '' }}</p>
            </div>
            
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    {{ $slot }}
                </div>
            </div>
        </div>

    </div>
</div>
