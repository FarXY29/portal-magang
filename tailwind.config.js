import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    // --- TRIK MEMATIKAN DARK MODE ---
    // Kita ubah strateginya menjadi 'selector' dan arahkan ke class palsu
    // Dengan begini, utility 'dark:...' tidak akan pernah aktif
    darkMode: ['selector', '.class-yang-tidak-akan-pernah-ada'], 
    // --------------------------------

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
        },
    },

    plugins: [forms],
};