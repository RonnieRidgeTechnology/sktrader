import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Open Sans', ...defaultTheme.fontFamily.sans],
                editorial: ['Fraunces', 'Georgia', 'serif'],
            },
            colors: {
                editorial: {
                    cream: '#f6f3ee',
                    paper: '#ebe6de',
                    ink: '#1c1917',
                    slate: '#44403c',
                    coral: '#c2410c',
                    sage: '#4d7c5c',
                },
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
                '4xl': '2rem',
            },
            boxShadow: {
                'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                'premium': '0 4px 24px -4px rgba(0, 0, 0, 0.08), 0 12px 32px -8px rgba(0, 0, 0, 0.06)',
                'premium-lg': '0 8px 40px -8px rgba(0, 0, 0, 0.12), 0 20px 48px -12px rgba(0, 0, 0, 0.08)',
                'inner-soft': 'inset 0 1px 0 0 rgba(255, 255, 255, 0.05)',
            },
            backgroundImage: {
                'gradient-premium': 'linear-gradient(135deg, var(--tw-gradient-from) 0%, var(--tw-gradient-to) 100%)',
            },
            keyframes: {
                'fade-in-up': {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                'float': {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                'float-slow': {
                    '0%, 100%': { transform: 'translateY(0) rotate(0deg)' },
                    '50%': { transform: 'translateY(-6px) rotate(1deg)' },
                },
                'scale-in': {
                    '0%': { opacity: '0', transform: 'scale(0.95)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                'shimmer': {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
                'draw-line': {
                    '0%': { strokeDashoffset: '1200' },
                    '100%': { strokeDashoffset: '0' },
                },
                'step-pop': {
                    '0%': { opacity: '0', transform: 'scale(0.8) translateY(12px)' },
                    '70%': { transform: 'scale(1.02) translateY(-2px)' },
                    '100%': { opacity: '1', transform: 'scale(1) translateY(0)' },
                },
                'route-dash': {
                    '0%': { strokeDashoffset: '0' },
                    '100%': { strokeDashoffset: '24' },
                },
                'pulse-soft': {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.7' },
                },
                'reveal-up': {
                    '0%': { opacity: '0', transform: 'translateY(28px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
            animation: {
                'fade-in-up': 'fade-in-up 0.6s ease-out forwards',
                'fade-in': 'fade-in 0.6s ease-out forwards',
                'float': 'float 4s ease-in-out infinite',
                'float-slow': 'float-slow 5s ease-in-out infinite',
                'scale-in': 'scale-in 0.5s ease-out forwards',
                'shimmer': 'shimmer 2s linear infinite',
                'draw-line': 'draw-line 1.2s ease-out forwards',
                'step-pop': 'step-pop 0.6s ease-out forwards',
                'route-dash': 'route-dash 1.2s linear infinite',
                'pulse-soft': 'pulse-soft 2.5s ease-in-out infinite',
                'reveal-up': 'reveal-up 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards',
            },
            animationDelay: {
                '200': '200ms',
                '300': '300ms',
                '400': '400ms',
                '500': '500ms',
                '600': '600ms',
                '700': '700ms',
                '800': '800ms',
            },
        },
    },

    plugins: [forms],
};
