import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: true,  // listen on all network interfaces
        hmr: {
            host: 'your-ngrok-subdomain.ngrok.io',  // replace this with your actual ngrok domain
        },
    },
    plugins: [
        laravel([
            'resources/css/app.css',
            'resources/js/app.js',
        ]),
    ],
});
