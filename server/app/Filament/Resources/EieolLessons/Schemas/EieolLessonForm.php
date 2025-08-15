<?php

namespace App\Filament\Resources\EieolLessons\Schemas;

use App\Filament\Forms\Components\TinyMceRichText;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EieolLessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('series_id')
                    ->relationship('series', 'title')
                    ->required(),
                Select::make('language_id')
                    ->relationship('language', 'language')
                    ->required(),
                TextInput::make('title')
                    ->default(null),
                TextInput::make('order')
                    ->required()
                    ->numeric(),
                TinyMceRichText::make('intro_text')
                    ->default(null)
                    ->columnSpanFull(),
                TinyMceRichText::make('lesson_text')
                    ->default(null)
                    ->columnSpanFull(),
                TinyMceRichText::make('lesson_translation')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
