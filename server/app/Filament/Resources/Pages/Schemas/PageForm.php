<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Filament\Forms\Components\TinyMceRichText;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->default(null),
                TextInput::make('name')
                    ->hintIcon('heroicon-m-language', tooltip: 'Translatable. Use the locale selector in the upper right to swap.')
                    ->default(null),
                TinyMceRichText::make('content')
                    ->hintIcon('heroicon-m-language', tooltip: 'Translatable. Use the locale selector in the upper right to swap.')
                    ->contentCss('/css/lrcstyle.css')
                    ->profile('page')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
