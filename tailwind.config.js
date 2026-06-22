import defaultTheme from 'tailwindcss/defaultTheme';
import colors from 'tailwindcss/colors';
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
            colors: {
                indigo: {
                    50: '#fff4ed',
                    100: '#ffe5d5',
                    200: '#ffc7a8',
                    300: '#ffa070',
                    400: '#fb7b3c',
                    500: '#e95f1d',
                    600: '#c94a13',
                    700: '#9f3712',
                    800: '#7f3016',
                    900: '#672b17',
                    950: '#371208',
                },
                gray: colors.stone,
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
