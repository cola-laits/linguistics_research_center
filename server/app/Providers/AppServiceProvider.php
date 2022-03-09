<?php

namespace App\Providers;

use App\Models\MenuItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $menu_items = MenuItem::orderBy('order')->get();
        View::share('menu_items', $menu_items);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // for running against mysql < 5.7
        Schema::defaultStringLength(191);
    }
}
