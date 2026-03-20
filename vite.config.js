import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 
                'resources/css/welcome_page.css',
                'resources/css/odysse_desert.css',
                'resources/css/login.css',
                'resources/css/widget_AI.css',
                'resources/css/traversees.css',
                'resources/js/welcome_page.js',
                'resources/js/app.js',
                'resources/js/odysse_desert.js',
                'resources/js/login.js',
                'resources/js/traversees.js',
                'resources/js/widget_AI.js',
                'resources/js/register.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
