import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/front.css',
                'resources/js/front.js',

                'resources/css/modules/chambre.css',
                'resources/js/forms/bungalow-form.js',
                'resources/css/modules/kayak.css',
                'resources/js/forms/kayak-form.js',
            ],
            refresh: true,
        }),
    ],
});
