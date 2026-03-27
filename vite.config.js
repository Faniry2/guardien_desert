import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 
                'resources/css/login.css',
                'resources/css/odysse_desert.css',
                
                'resources/css/traversees.css',
                'resources/css/welcome_page.css',
                'resources/css/widget_AI.css',

                'resources/js/app.js',
                'resources/js/login.js',
                'resources/js/odysse_desert.js',
                'resources/js/register.js',
                'resources/js/traversees.js',
                'resources/js/welcome_page.js',
                'resources/js/widget_AI.js',
                'resources/js/crypto.js'
            ],
            refresh: true,
        }),
    ],
});
