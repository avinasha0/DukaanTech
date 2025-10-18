import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            colors: {
                brand: {
                    DEFAULT: '#F97316',
                    50: '#FFF7ED',
                    100: '#FFEDD5',
                    500: '#F97316',
                    600: '#EA580C',
                    700: '#C2410C',
                },
                'royal-purple': {
                    DEFAULT: '#6E46AE',
                    50: '#F3F0F7',
                    100: '#E6DDF0',
                    200: '#D1C2E3',
                    300: '#B8A0D1',
                    400: '#9B7BBE',
                    500: '#6E46AE',
                    600: '#5D3A9A',
                    700: '#4E2F82',
                    800: '#40256A',
                    900: '#321C52',
                },
                'tiffany-blue': {
                    DEFAULT: '#00B6B4',
                    50: '#E6F9F9',
                    100: '#CCF3F2',
                    200: '#99E7E5',
                    300: '#66DBD8',
                    400: '#33CFCB',
                    500: '#00B6B4',
                    600: '#009290',
                    700: '#006D6C',
                    800: '#004848',
                    900: '#002424',
                },
                ink: {
                    900: '#111827',
                    700: '#374151',
                    500: '#6B7280',
                    300: '#D1D5DB',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                heading: ['DM Sans', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                soft: '0 12px 30px rgba(2,6,23,.08)',
            },
            screens: {
                xs: '360px',
            },
            borderRadius: {
                '2xl': '1rem',
            },
            transitionDuration: {
                '200': '200ms',
            },
        },
    },

    plugins: [forms],
};
