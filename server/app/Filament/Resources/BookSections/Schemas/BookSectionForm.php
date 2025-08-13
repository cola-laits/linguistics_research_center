<?php

namespace App\Filament\Resources\BookSections\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BookSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('book_id')
                    ->relationship('book', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('order')
                    ->required()
                    ->numeric(),
                Textarea::make('content')
                    ->required()
                    ->rows(20)
                    ->columnSpanFull(),
            ]);
    }
}
