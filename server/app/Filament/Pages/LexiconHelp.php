<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use UnitEnum;

class LexiconHelp extends Page
{
    protected string $view = 'filament.pages.lexicon-help';

    protected static ?string $title = 'Lexicon Editor Help';
    protected static string | UnitEnum | null $navigationGroup = 'Lexicon';
    protected static ?int $navigationSort = -1;
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-question-mark-circle';

    public static function canAccess(): bool
    {
        return auth()->user()->can('manage_lexicon');
    }

}
