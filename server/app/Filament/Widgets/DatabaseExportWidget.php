<?php

namespace App\Filament\Widgets;

use App\Helpers\SanitizedDatabaseDump;
use Filament\Widgets\Widget;

class DatabaseExportWidget extends Widget
{
    protected string $view = 'filament.widgets.database-export-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -2;

    public static function canView(): bool
    {
        return auth()->user()?->can('export', SanitizedDatabaseDump::class) ?? false;
    }
}
