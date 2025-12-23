import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: true, // Listen on all addresses including LAN
        hmr: {
            host: '192.168.33.3', // Your computer's IP address
        },
    },
});
