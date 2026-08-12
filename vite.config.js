import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

const ddevPrimaryUrl = process.env.DDEV_PRIMARY_URL;

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/filament/panel.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        ...(ddevPrimaryUrl
            ? {
                  origin: `${ddevPrimaryUrl.replace(/:\d+$/, '')}:5173`,
                  cors: {
                      origin: /https?:\/\/([A-Za-z0-9\-\.]+)?(\.ddev\.site)(?::\d+)?$/,
                  },
              }
            : {}),
    },
});
