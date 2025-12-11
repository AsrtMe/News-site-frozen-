import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                navy: {
                    50: '#f3f6fb',
                    100: '#e6eef8',
                    200: '#cde0f0',
                    300: '#9fbbe6',
                    400: '#6f8fd9',
                    500: '#3f63cc',
                    600: '#2f4fa0',
                    700: '#20376f',
                    800: '#162643',
                    900: '#0b1726'
                }
            },
        },
    },

    plugins: [forms],
};
