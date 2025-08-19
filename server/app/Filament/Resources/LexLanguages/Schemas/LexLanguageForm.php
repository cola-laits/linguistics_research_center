<?php

namespace App\Filament\Resources\LexLanguages\Schemas;

use App\Filament\Forms\Components\TinyMceRichText;
use App\Models\LexLanguage;
use App\Models\LexLanguageSubFamily;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LexLanguageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('name')
                    ->hintIcon('heroicon-m-language', tooltip: 'Translatable. Use the locale selector in the upper right to swap.')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('order')
                    ->required()
                    ->numeric(),
                TextInput::make('abbr')
                    ->default(null),
                TextInput::make('aka')
                    ->default(null),
                Select::make('language_sub_family')
                    ->relationship('language_sub_family', 'name')
                    ->getOptionLabelFromRecordUsing(fn (LexLanguageSubFamily $sub) => $sub->familySubFamily)
                    ->required(),
                TextInput::make('override_family')
                    ->default(null)
                    ->helperText('This is for the reflex page. This value will show instead of the Family that this Language belongs to.'),
                TinyMceRichText::make('description')
                    ->hintIcon('heroicon-m-language', tooltip: 'Translatable. Use the locale selector in the upper right to swap.')
                    ->contentCss('/css/lexicon.css')
                    ->profile('page')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
