<?php

namespace App\Filament\Resources\LexLexicons\Schemas;

use App\Filament\Forms\Components\TinyMceRichText;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LexLexiconForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('protolang_name')
                    ->hintIcon('heroicon-m-language', tooltip: 'Translatable. Use the locale selector in the upper right to swap.')
                    ->required()
                    ->columnSpanFull(),
                TinyMceRichText::make('landing_page_content')
                    ->hintIcon('heroicon-m-language', tooltip: 'Translatable. Use the locale selector in the upper right to swap.')
                    ->default(null)
                    ->contentCss('/css/lexicon.css')
                    ->profile('page')
                    ->columnSpanFull(),
                TinyMceRichText::make('protolanguage_page_content')
                    ->hintIcon('heroicon-m-language', tooltip: 'Translatable. Use the locale selector in the upper right to swap.')
                    ->default(null)
                    ->contentCss('/css/lexicon.css')
                    ->profile('page')
                    ->columnSpanFull(),
                TextInput::make('viewer_lang_options')
                    ->default(null)
                    ->helperText('Comma-separated list of language codes to offer to the user. ex: "en, es"'),
            ]);
    }
}
