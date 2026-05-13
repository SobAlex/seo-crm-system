/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.vue',
        './resources/**/*.js',
        './resources/**/*.ts',
    ],
    theme: {
        extend: {},
    },
    plugins: [],
    input: ['resources/css/app.css', 'resources/js/app.ts'],
};
