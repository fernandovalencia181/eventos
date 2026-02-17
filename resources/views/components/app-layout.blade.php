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
    
    {{-- Google Translate --}}
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'es',
                includedLanguages: 'ca,es,en',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <style>
        .goog-te-banner-frame { display: none !important; }
        body { top: 0 !important; }
        .goog-te-gadget { font-size: 0 !important; }
        .goog-te-gadget .goog-te-combo {
            padding: 6px 10px;
            border-radius: 8px;
            border: 1px solid rgba(99, 102, 241, 0.3);
            background: rgba(30, 41, 59, 0.8);
            color: #e2e8f0;
            font-size: 13px;
            cursor: pointer;
            outline: none;
        }
        .goog-te-gadget .goog-te-combo:hover {
            border-color: rgba(99, 102, 241, 0.6);
        }
        .skiptranslate { display: none !important; }
        .goog-te-spinner-pos { display: none !important; }
    </style>
    
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="font-sans antialiased bg-secondary-50 dark:bg-primary-950 text-secondary-900 dark:text-gray-100 transition-colors duration-300">
    
    <div class="min-h-screen flex flex-col">
        
        @include('partials.navigation-menu')

        <main class="flex-grow">
            {{ $slot }}
        </main>
        
        @include('partials.footer')

    </div>

    @livewireScripts
</body>
</html>