import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
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
                // Paleta Blau Fort (#033473) - Modern & Corporate
                primary: {
                    50: '#f0f5fa',
                    100: '#e1ebf5',
                    200: '#c2d7eb',
                    300: '#94b9de',
                    400: '#5793cf',
                    500: '#3074b8',
                    600: '#033473', // Blau Fort (Base)
                    700: '#022a5c',
                    800: '#022046',
                    900: '#011530',
                    950: '#010d1e',
                },
                // Secondary (Gris Neutro/Plata) - Reemplaza al amarillo anterior para un look más limpio
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
                    950: '#020617',
                },
                // Neutrals (Cool Gray/Slate for modern feel)
                gray: {
                    50: '#f9fafb',
                    100: '#f3f4f6',
                    200: '#e5e7eb',
                    300: '#d1d5db',
                    400: '#9ca3af',
                    500: '#6b7280',
                    600: '#4b5563',
                    700: '#374151',
                    800: '#1f2937',
                    900: '#111827',
                    950: '#030712',
                },
                success: '#10b981',
                danger: '#ef4444',
                warning: '#eab308', // Amarillo estandard para warnings solamente
                info: '#033473',
            },
        },
    },
    plugins: [],
};