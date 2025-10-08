<?php

namespace App\Filament\Resources\LexSemanticFields\Schemas;

use App\Models\LexSemanticCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LexSemanticFieldForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('number')
                    ->default(null),
                TextInput::make('abbr')
                    ->default(null),
                Select::make('semantic_category_id')
                    ->relationship('semantic_category', 'abbr')
                    ->getOptionLabelFromRecordUsing(fn (LexSemanticCategory $category) => "{$category->lexicon->name}: {$category->abbr}")
                    ->required(),
                Textarea::make('text')
                    ->hintIcon('heroicon-m-language', tooltip: 'Translatable. Use the locale selector in the upper right to swap.')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
