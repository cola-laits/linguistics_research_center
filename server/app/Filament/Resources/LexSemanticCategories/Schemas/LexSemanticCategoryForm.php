<?php

namespace App\Filament\Resources\LexSemanticCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LexSemanticCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('lexicon_id')
                    ->relationship('lexicon', 'name')
                    ->required(),
                TextInput::make('number')
                    ->default(null),
                TextInput::make('abbr')
                    ->default(null),
                Textarea::make('text')
                    ->hintIcon('heroicon-m-language', tooltip: 'Translatable. Use the locale selector in the upper right to swap.')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
