import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        // Configuration pour la production
        rollupOptions: {
            output: {
                manualChunks: undefined,
            },
        },
    },
    // Configuration pour forcer HTTPS en production
    server: {
        https: process.env.APP_ENV === 'production',
        host: process.env.APP_ENV === 'production' ? 'staff.nigerdev.com' : 'localhost',
    },
    // Base URL pour les assets
    base: process.env.APP_ENV === 'production' ? 'https://staff.nigerdev.com/' : '/',
});
