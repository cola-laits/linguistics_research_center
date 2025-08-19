<?php

namespace App\Filament\Resources\LexSources\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LexSourceForm
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
                TextInput::make('display')
                    ->default(null),
            ]);
    }
}
