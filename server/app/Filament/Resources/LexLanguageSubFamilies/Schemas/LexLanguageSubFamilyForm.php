<?php

namespace App\Filament\Resources\LexLanguageSubFamilies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LexLanguageSubFamilyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('family_id')
                    ->relationship('language_family', 'name')
                    ->required(),
                TextInput::make('order')
                    ->required()
                    ->numeric(),
                Textarea::make('name')
                    ->hintIcon('heroicon-m-language', tooltip: 'Translatable. Use the locale selector in the upper right to swap.')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
