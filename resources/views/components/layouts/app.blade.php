<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
</head>
<body class="font-sans antialiased bg-secondary-50 text-secondary-900">
    
    <div class="min-h-screen flex flex-col">
        @include('navigation-menu')

        <main class="flex-grow">
            {{ $slot }}
        </main>
        
        @include('footer')
    </div>

    @livewireScripts
</body>
</html>