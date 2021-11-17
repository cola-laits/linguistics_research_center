const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .js('resources/js/admin.js', 'public/js').vue()
    .sass('resources/sass/admin.scss', 'public/css')
    .copyDirectory('node_modules/tinymce/icons','public/js/icons')
    .sourceMaps()
    .extract([])
    .disableNotifications()
    .options({
        terser: {
            extractComments: false,
            terserOptions: {
                output: {
                    comments: false,
                },
            },
        },
    })
;

if (mix.inProduction()) {
    mix.version();
}
