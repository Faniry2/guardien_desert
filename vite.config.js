import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/login.css',
                'resources/css/carnet.css',
                'resources/css/traversees.css',
                'resources/css/welcome_page.css',
                'resources/css/widget_AI.css',
                'resources/css/djanet.css',
                'resources/css/form.css',
                'resources/js/app.js',
                'resources/js/login.js',
                'resources/js/carnet.js',
                'resources/js/widget_AI.js',
                'resources/js/crypto.js',
                'resources/js/djanet.js',
                'resources/js/form.js'
            ],
            refresh: true,
        }),
    ],
});
