<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EventosU') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-primary-950 text-gray-900 dark:text-gray-100 transition-colors duration-300">
        <div class="min-h-screen flex flex-col">
            
            {{-- Navigation Menu (Shared with App Layout) --}}
            @include('partials.navigation-menu')

            <div class="flex-grow flex flex-col justify-center items-center pt-6 sm:pt-0 px-4 py-12">
                {{-- Logo (Visible on mobile or small screens above card) --}}
                <div class="mb-6 sm:mb-8 md:hidden">
                    <a href="/" class="flex items-center gap-2">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-cyan-500 rounded-xl flex items-center justify-center text-white shadow-lg">
                             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </div>
                    </a>
                </div>

                <div class="w-full sm:max-w-md px-6 py-8 bg-white dark:bg-primary-900 shadow-xl overflow-hidden rounded-2xl border border-gray-200 dark:border-primary-800 transition-colors">
                    {{ $slot }}
                </div>
            </div>

            @include('partials.footer')
        </div>
        @livewireScripts
    </body>
</html>
