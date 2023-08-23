<?php

namespace App\Providers;

use App\Models\Issue;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class ViewServiceProvider extends ServiceProvider
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
        Facades\View::composer('admin_layout', function(View $view) {
            $issues = Issue::where('status', 'open');
            if (Auth::user()?->isAdmin()) {
                $numOpenIssues = $issues->count();
            } else {
                $serieses = Auth::user()->editableSeries->sortBy('order');
                $issues = $issues->where(function($query) use ($serieses) {
                    foreach ($serieses as $series) {
                        foreach ($series->lessons as $lesson) {
                            $query->orWhere('pointer', 'like', '/lesson/' . $lesson->id . '/%');
                        }
                    }
                });
                $issues = $issues->distinct();
                $numOpenIssues = $issues->count();
            }
            $view->with('numOpenIssues', $numOpenIssues);
        });
    }
}
