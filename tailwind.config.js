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
                // Paleta basada en #033473 (blau fosc) i #F2E205 (groc)
                primary: {
                    50: '#e6eef5',
                    100: '#ccdde9',
                    200: '#99bbd3',
                    300: '#6699bd',
                    400: '#3377a7',
                    500: '#035591', // Variació més clara del blau principal
                    600: '#033473', // Color principal
                    700: '#022857',
                    800: '#021c3b',
                    900: '#01101f',
                    950: '#00080f',
                },
                secondary: {
                    50: '#fefce8',
                    100: '#fef9c3',
                    200: '#fef08a',
                    300: '#fde047',
                    400: '#facc15',
                    500: '#F2E205', // Groc principal
                    600: '#ca8a04',
                    700: '#a16207',
                    800: '#854d0e',
                    900: '#713f12',
                },
                success: '#10b981',
                danger: '#ef4444',
                warning: '#F2E205', // Mateix groc per warnings
                info: '#033473', // Blau per info
            },
        },
    },
    plugins: [],
};