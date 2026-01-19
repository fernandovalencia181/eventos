<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EventosU') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-secondary-900 antialiased bg-secondary-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-6">
                <a href="/" class="text-4xl font-bold text-primary-600 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v4.086c0 .698.509 1.3 1.209 1.36a11.97 11.97 0 005.996 4.024q.16.035.32.065V19.5a3 3 0 003 3h5.25a3 3 0 003-3v-2.25a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 9.375v-1.5h3.375c.621 0 1.125-.504 1.125-1.125V5.25c0-.621-.504-1.125-1.125-1.125h-13.5c-.621 0-1.125.504-1.125 1.125z" />
                    </svg>
                    EventosU
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-lg overflow-hidden sm:rounded-xl border border-secondary-200">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>