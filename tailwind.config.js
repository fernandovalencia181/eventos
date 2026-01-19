import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/livewire/flux/resources/views/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.jsx',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Paleta generada basada en el Azul La Salle Mollerussa
                primary: {
                    50: '#f0f5fa',  // Fondo muy claro
                    100: '#e1ebf5',
                    200: '#c3d7eb',
                    300: '#a5c3e1',
                    400: '#699ccd',
                    500: '#2d75b9', // Azul vibrante para botones/acciones
                    600: '#245e94',
                    700: '#1b466f',
                    800: '#122f4a', // Tono cercano al logo
                    900: '#0a1d30', // Tono más oscuro del fondo
                    950: '#050f1a', // Extra oscuro
                },
                secondary: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                },
                success: '#10b981',
                danger: '#ef4444',
                warning: '#f59e0b',
                info: '#3b82f6',
            },
        },
    },
    plugins: [],
};