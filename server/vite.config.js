import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';
import { viteStaticCopy } from 'vite-plugin-static-copy'
import { normalizePath } from 'vite';
import path from 'node:path';

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
        viteStaticCopy({
            targets: [
                {
                    src: normalizePath(path.resolve(__dirname, 'node_modules', 'tinymce')),
                    dest: normalizePath(path.resolve(__dirname, 'public', 'build'))
                }]
        })
    ],
});
