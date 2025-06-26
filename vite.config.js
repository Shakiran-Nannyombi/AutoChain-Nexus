import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: 'localhost',
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/auth.css',
                'resources/css/admin.css',
                'resources/css/welcome.css',
                'resources/css/manufacturer.css',
                'resources/css/analyst.css',
                'resources/css/supplier.css',
                'resources/css/retailer.css',
                'resources/css/vendor.css',
                'resources/js/app.js',
                'resources/js/welcome.js',
            ],
            refresh: true,
        }),
    ],
});
