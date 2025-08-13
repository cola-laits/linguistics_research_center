import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    resolve: {
        alias: {
            '~bootstrap': 'bootstrap',
            'vue': 'vue/dist/vue.esm-bundler.js',
        }
    },
    plugins: [
        laravel({
            input: [
                'resources/sass/admin.scss',
                'resources/js/admin.js',
                'resources/css/filament/admin/theme.css',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        tailwindcss(),
    ],
});
