<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'EventosU') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-secondary-50 text-secondary-900">
    
    <div class="min-h-screen flex flex-col">
        
        @include('navigation-menu')

        <main class="flex-grow">
            {{ $slot }}
        </main>
        
        <footer class="bg-white border-t border-secondary-200 mt-auto">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-secondary-400">
                    &copy; {{ date('Y') }} EventosU. Todos los derechos reservados.
                </p>
            </div>
        </footer>

    </div>

    @livewireScripts
</body>
</html>