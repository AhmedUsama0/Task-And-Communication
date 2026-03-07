const defaultTheme = require('tailwindcss/defaultTheme');
// Default colors used by default theme (separated since tailiwnd v3)
const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
export default {
    // need explaination
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js'
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

            container: {
                center: true
            },

            fontSize: {
                h1: ['2.5rem', {
                    lineHeight: 1.2,
                    fontWeight: 700
                }],
                h2: ['2rem', {
                    fontWeight: 700
                }],
                h3: ['1.5rem', {
                    fontWeight: 600
                }]
            },

            /**
             * define colors for all utilties such as (text,background,border..etc)
             * we override a specific shade of a color if we re-define it while other shades keeps untouched
             */
            colors:{
                primary: {
                    DEFAULT: '#0178FF',
                    lighter: '#D6F2FF'
                },
                grey: {
                    '50': '#F9FBFC',
                    '100': '#F3F5F7',
                    '200': '#ECEEF2'
                },
                yellow: {
                    DEFAULT: '#FFF1E1',
                    darker: '#B57116'
                },
                red: {
                    DEFAULT: '#FFE9E8',
                    darker: '#A43B4A',
                },
                green: {
                    DEFAULT: '#E2FEE9',
                    darker: '#118E3F'
                }
            },

            // override text-{color}
            textColor: {
                primary: colors.black
            },

            height: {
                17.5: '70px',
                75: '300px'
            },

            inset: {
                14.25: '57px'
            },

            maxHeight: {
                125: '500px'
            },

            gridTemplateRows: {
                'body-grid-row-mobile': '70px 1fr'
            }
        },
    },
    // darkMode: 'selector',
    plugins: [
        require('@tailwindcss/forms')
    ],
};

// Need to understand docker and remove those suggestion from terminal