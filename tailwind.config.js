const plugin = require('tailwindcss/plugin');
const withMT = require("@material-tailwind/html/utils/withMT");

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./node_modules/flowbite/**/*.js",
    ],

    theme: {
        extend: {
            opacity: {
                '0': '0',
                '25': '0.25',
                '50': '0.5',
                '75': '0.75',
                '100': '1',
            },
            boxShadow: {
                DEFAULT: '0 1px 3px 0 rgba(0, 0, 0, 0.08), 0 1px 2px 0 rgba(0, 0, 0, 0.02)',
                md: '0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -1px rgba(0, 0, 0, 0.02)',
                lg: '0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.01)',
                xl: '0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.01)',
            },
            outline: {
                blue: '2px solid rgba(0, 112, 244, 0.5)',
            },
            fontFamily: {
                inter: ['Tajawal'],
                itim: ['Tajawal'],
                body: [
                    'Tajawal'
                ],
                sans: [
                    'Tajawal'
                ],
                'display': ['Tajawal'],

            },            fontSize: {
                xs: ['0.75rem', {lineHeight: '1.5'}],
                sm: ['0.875rem', {lineHeight: '1.5715'}],
                base: ['1rem', {lineHeight: '1.5', letterSpacing: '-0.01em'}],
                lg: ['1.125rem', {lineHeight: '1.5', letterSpacing: '-0.01em'}],
                xl: ['1.25rem', {lineHeight: '1.5', letterSpacing: '-0.01em'}],
                '2xl': ['1.5rem', {lineHeight: '1.33', letterSpacing: '-0.01em'}],
                '3xl': ['1.88rem', {lineHeight: '1.33', letterSpacing: '-0.01em'}],
                '4xl': ['2.25rem', {lineHeight: '1.25', letterSpacing: '-0.02em'}],
                '5xl': ['3rem', {lineHeight: '1.25', letterSpacing: '-0.02em'}],
                '6xl': ['3.75rem', {lineHeight: '1.2', letterSpacing: '-0.02em'}],
            },
            screens: {
                xs: '480px',
            },
            borderWidth: {
                3: '3px',
            },
            minWidth: {
                36: '9rem',
                44: '11rem',
                56: '14rem',
                60: '15rem',
                72: '18rem',
                80: '20rem',
            },
            maxWidth: {
                '8xl': '88rem',
                '9xl': '96rem',
            },
            zIndex: {
                60: '60',
            },
            colors: {
                header: {
                    'color-1': "#E7E8FF",
                },
                blue: {
                    'color-1-darker': "#070846",
                    'color-1-dark': "#0C0F64",
                    'color-1': "#161A7D",
                    'color-1-light': "#414280",
                    'color-1-soft': "#B8BAD4",
                    'color-1-lighter': "#EBECF8",
                },
                yellow: {
                    'color-2-darker': "#3F4E0D",
                    'color-2-dark': "#8AAE0E",
                    'color-2': "#C8FF0D",
                    'color-2-light': "#DFFF72",
                    'color-2-lighter': "#E7EDD2",
                },
                primary: {
                    "50": "#eff6ff",
                    "100": "#dbeafe",
                    "200": "#bfdbfe",
                    "300": "#93c5fd",
                    "400": "#60a5fa",
                    "500": "#3b82f6",
                    "600": "#2563eb",
                    "700": "#1d4ed8",
                    "800": "#1e40af",
                    "900": "#1e3a8a",
                }, //#2A2B69 #34A853 #B8BAD4
                base: {
                    "blue": "#2A2B69",
                    "green": "#34A853",
                    "background": "#B8BAD4",
                    "create": "#2A2B69",
                }

            },
        },
    },
    darkMode: 'class',
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        plugin(({addVariant, e}) => {
            addVariant('sidebar-expanded', ({modifySelectors, separator}) => {
                modifySelectors(({className}) => `.sidebar-expanded .${e(`sidebar-expanded${separator}${className}`)}`);
            });
        }),
    ],
};

