import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/css/app.css',
            ],
            refresh: true,
        }),
    ],
    server: {
        proxy: {
        // Proxy requests from Vite -> Laravel (Apache)
            '/proxy': {
                target: 'http://webviewer.test', // <-- your Apache/Laravel host
                changeOrigin: true,
                secure: false,
            },
            // Optional: also proxy /api
            // '/api': {
            //     target: 'http://localhost',
            //     changeOrigin: true,
            //     secure: false,
            // },
        },
    },
});
