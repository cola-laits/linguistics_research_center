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
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

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
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')

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

                NavigationItem::make('Settings')
                    ->group('General')
                    ->visible(fn() => auth()->user()->can('manage_settings'))
                    ->url('/admin_mgr/setting')->sort(2),
                NavigationItem::make('Pages')
                    ->group('General')
                    ->visible(fn() => auth()->user()->can('manage_pages'))
                    ->url('/admin_mgr/page')->sort(3),
                NavigationItem::make('Menu Items')
                    ->group('General')
                    ->visible(fn() => auth()->user()->can('manage_menu'))
                    ->url('/admin_mgr/menu-item')->sort(4),

                NavigationItem::make('Help')
                    ->group('Lexicon')
                    ->visible(fn() => auth()->user()->can('manage_lexicon'))
                    ->url('/admin_mgr/help_lex')->sort(1),
                NavigationItem::make('Lexicons')
                    ->group('Lexicon')
                    ->visible(fn() => auth()->user()->can('manage_lexicon'))
                    ->url('/admin_mgr/lex-lexicon')->sort(2),
                NavigationItem::make('Language Families')
                    ->group('Lexicon')
                    ->visible(fn() => auth()->user()->can('manage_lexicon'))
                    ->url('/admin_mgr/lex_language_family')->sort(3),
                NavigationItem::make('Language Sub Families')
                    ->group('Lexicon')
                    ->visible(fn() => auth()->user()->can('manage_lexicon'))
                    ->url('/admin_mgr/lex_language_sub_family')->sort(4),
                NavigationItem::make('Languages')
                    ->group('Lexicon')
                    ->visible(fn() => auth()->user()->can('manage_lexicon'))
                    ->url('/admin_mgr/lex_language')->sort(5),
                NavigationItem::make('Etyma')
                    ->group('Lexicon')
                    ->visible(fn() => auth()->user()->can('manage_lexicon'))
                    ->url('/admin_mgr/lex_etyma')->sort(6),
                NavigationItem::make('Reflexes')
                    ->group('Lexicon')
                    ->visible(fn() => auth()->user()->can('manage_lexicon'))
                    ->url('/admin_mgr/lex_reflex')->sort(7),
                NavigationItem::make('Sources')
                    ->group('Lexicon')
                    ->visible(fn() => auth()->user()->can('manage_lexicon'))
                    ->url('/admin_mgr/lex_source')->sort(8),
                NavigationItem::make('Semantic Categories')
                    ->group('Lexicon')
                    ->visible(fn() => auth()->user()->can('manage_lexicon'))
                    ->url('/admin_mgr/lex_semantic_category')->sort(9),
                NavigationItem::make('Semantic Fields')
                    ->group('Lexicon')
                    ->visible(fn() => auth()->user()->can('manage_lexicon'))
                    ->url('/admin_mgr/lex_semantic_field')->sort(10),
                NavigationItem::make('Parts of Speech')
                    ->group('Lexicon')
                    ->visible(fn() => auth()->user()->can('manage_lexicon'))
                    ->url('/admin_mgr/lex_part_of_speech')->sort(11),

                NavigationItem::make('Languages')
                    ->group('EIEOL')
                    ->visible(fn() => auth()->user()->can('manage_eieol'))
                    ->url('/admin_mgr/eieol_language')->sort(1),
                NavigationItem::make('Series')
                    ->group('EIEOL')
                    ->visible(fn() => auth()->user()->can('manage_eieol'))
                    ->url('/admin_mgr/eieol_series')->sort(2),
                NavigationItem::make('Lessons')
                    ->group('EIEOL')
                    ->visible(fn() => auth()->user()->can('manage_eieol'))
                    ->url('/admin_mgr/eieol-lesson')->sort(3),

                NavigationItem::make('Books')
                    ->group('Books')
                    ->visible(fn() => auth()->user()->can('manage_books'))
                    ->url('/admin_mgr/book')->sort(1),
                NavigationItem::make('Book Sections')
                    ->group('Books')
                    ->visible(fn() => auth()->user()->can('manage_books'))
                    ->url('/admin_mgr/book-section')->sort(2),
            ])
            ->navigationGroups([
                'General',
                'Lexicon',
                'EIEOL',
                'Books',
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
