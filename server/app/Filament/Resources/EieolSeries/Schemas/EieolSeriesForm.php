<?php

namespace App\Filament\Resources\EieolSeries\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EieolSeriesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->default(null),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('expanded_title')
                    ->default(null),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->helperText("Order when displayed in a list (e.g. 'The Lesson Texts' on EIEOL home page)"),
                TextInput::make('menu_name')
                    ->default(null),
                TextInput::make('menu_order')
                    ->helperText("Order when displayed in left-hand menu ('EIEOL Lessons < Lessons')")
                    ->default(null),
                Checkbox::make('published'),
                Textarea::make('meta_tags')
                    ->default(null)
                    ->columnSpanFull(),
            ])->columns(1);
    }
}
