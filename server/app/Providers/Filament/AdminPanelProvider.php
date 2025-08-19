<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\SeriesEditorNavWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->maxContentWidth(Width::Full)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->plugin(
                SpatieTranslatablePlugin::make()->defaultLocales(['en', 'es'])
            )

            // FIXME: temporary nav item redirects for things that haven't been converted from Backpack yet
            ->navigationItems([
                NavigationItem::make('Issues')
                    ->url('/admin2/issues')
                    ->icon('heroicon-o-bell')
                    ->badge(function() {
                        $issues = \App\Models\Issue::where('status', 'open');
                        if (!auth()->user()?->isAdmin()) {
                            $serieses = auth()->user()->editableSeries->sortBy('order');
                            $issues = $issues->where(function ($query) use ($serieses) {
                                foreach ($serieses as $series) {
                                    foreach ($series->lessons as $lesson) {
                                        $query->orWhere('pointer', 'like', '/lesson/' . $lesson->id . '/%');
                                    }
                                }
                            });
                            $issues = $issues->distinct();
                        }
                        return $issues->count();
                    }),
            ])
            ->navigationGroups([
                NavigationGroup::make('General'),
                NavigationGroup::make('Lexicon'),
                NavigationGroup::make('EIEOL'),
                NavigationGroup::make('Books'),
            ])
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                SeriesEditorNavWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
