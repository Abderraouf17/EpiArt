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
                sans: ['Instrument Sans', 'sans-serif'],
                serif: ['Playfair Display', 'serif'],
            },
            colors: {
                spice: {
                    50: '#fdf9f3',
                    500: '#8B3A3A',
                    600: '#722F37',
                    700: '#5a1e25',
                    800: '#991b1b',
                    900: '#7f1d1d',
                },
                beauty: {
                    50: '#fdf4ff',
                    100: '#fae8ff',
                    500: '#d946ef',
                    600: '#c026d3',
                    800: '#86198f',
                    900: '#701a75',
                }
            }
        },
    },

    plugins: [forms],
};
