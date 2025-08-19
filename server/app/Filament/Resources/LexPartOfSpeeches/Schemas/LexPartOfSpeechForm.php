<?php

namespace App\Filament\Resources\LexPartOfSpeeches\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LexPartOfSpeechForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('lexicon')
                    ->relationship('lexicon', 'name')
                    ->required(),
                TextInput::make('code')
                    ->default(null),
                Textarea::make('display')
                    ->hintIcon('heroicon-m-language', tooltip: 'Translatable. Use the locale selector in the upper right to swap.')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
