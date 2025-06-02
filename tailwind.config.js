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
                sans: ['Figtree', 'Inter', 'Roboto', 'Open Sans', ...defaultTheme.fontFamily.sans],
                serif: ['Merriweather', 'Georgia', ...defaultTheme.fontFamily.serif],
                mono: ['Fira Mono', 'Menlo', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                primary: '#232c5e',      // slate-900
                secondary: '#33b1e7',    // blue-600
                accent: '#2196f3',       // blue-500
                text: '#ffffff',         // white
                background: '#ffffff',   // white
            },
        },
    },
    plugins: [forms],
};
