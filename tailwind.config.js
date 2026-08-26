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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Brand palette CSS variables se aati hai (resources/css/app.css ke
                // ":root" block mein). Theme badalne ke liye wahan values edit karo.
                brand: {
                    50: 'rgb(var(--color-brand-50) / <alpha-value>)',
                    100: 'rgb(var(--color-brand-100) / <alpha-value>)',
                    200: 'rgb(var(--color-brand-200) / <alpha-value>)',
                    300: 'rgb(var(--color-brand-300) / <alpha-value>)',
                    400: 'rgb(var(--color-brand-400) / <alpha-value>)',
                    500: 'rgb(var(--color-brand-500) / <alpha-value>)',
                    600: 'rgb(var(--color-brand-600) / <alpha-value>)',
                    700: 'rgb(var(--color-brand-700) / <alpha-value>)',
                    800: 'rgb(var(--color-brand-800) / <alpha-value>)',
                    900: 'rgb(var(--color-brand-900) / <alpha-value>)',
                    950: 'rgb(var(--color-brand-950) / <alpha-value>)',
                },
            },
            keyframes: {
                'fade-in-up': {
                    '0%': { opacity: 0, transform: 'translateY(16px)' },
                    '100%': { opacity: 1, transform: 'translateY(0)' },
                },
            },
            animation: {
                'fade-in-up': 'fade-in-up 0.5s ease-out both',
            },
        },
    },

    plugins: [forms],
};
