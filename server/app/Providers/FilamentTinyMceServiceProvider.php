<?php

namespace App\Providers;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;

class FilamentTinyMceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // make filament aware of core tinymce js/css
        FilamentAsset::register([
            Js::make('tinymce', asset('build/tinymce/tinymce.min.js')),
            Css::make('tinymce', asset('build/tinymce/skins/ui/oxide/skin.min.css')),
        ]);

        // use filament to point tinymce to its asset folders (copied by viteStaticCopy in vite.config.js)
        FilamentAsset::registerScriptData([
            'tinymceBaseUrl' => asset('build/tinymce'),
        ]);
    }
}
