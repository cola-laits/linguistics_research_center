<?php

namespace App\Filament\Resources\LexEtymas\Schemas;

use App\Models\LexEtyma;
use App\Models\LexReflex;
use App\Models\LexSemanticField;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LexEtymaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id')
                    ->disabled()
                    ->readonly(),
                Select::make('lexicon_id')
                    ->relationship('lexicon', 'name')
                    ->columnSpanFull()
                    ->required(),
                TextInput::make('old_id')
                    ->default(null),
                TextInput::make('order')
                    ->required()
                    ->numeric(),
                TextInput::make('page_number')
                    ->default(null),
                TextInput::make('homograph_number')
                    ->default(null),
                TextInput::make('entry')
                    ->label('Etyma')
                    ->default(null),
                Textarea::make('gloss')
                    ->hintIcon('heroicon-m-language', tooltip: 'Translatable. Use the locale selector in the upper right to swap.')
                    ->default(null)
                    ->columnSpanFull(),

                Select::make('cross_references')
                    ->relationship('cross_references', 'entry')
                    ->multiple()
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->lexiconNameEntryGloss)
                    ->searchPrompt("Search for an Etymon by entry or etymon id...")
                    ->getSearchResultsUsing(function (string $search) {
                        return LexEtyma::query()
                            ->where('entry', 'like', '%'.$search.'%')
                            ->orWhere('id', $search)
                            ->limit(250)
                            ->get()
                            ->pluck('lexicon_name_entry_gloss', 'id')
                            ->toArray();
                    }),

                Select::make('semantic_fields')
                    ->relationship('semantic_fields', 'text')
                    ->multiple()
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->lexiconNameAbbrText)
                    ->searchPrompt("Search for a Semantic Field by abbreviation or text...")
                    ->getSearchResultsUsing(function (string $search) {
                        return LexSemanticField::query()
                            ->where('abbr', 'like', '%'.$search.'%')
                            ->orWhere('text->en', 'like', '%'.$search.'%')
                            ->limit(250)
                            ->get()
                            ->pluck('lexicon_name_abbr_text', 'id')
                            ->toArray();
                    }),

                Select::make('reflexes')
                    ->columnSpanFull()
                    ->relationship('reflexes', 'langNameEntriesGloss')
                    ->multiple()
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->langNameEntriesGloss)
                    ->searchPrompt("Search for a Reflex by English gloss or reflex id...")
                    ->getSearchResultsUsing(function (string $search) {
                        return LexReflex::query()
                            ->where('gloss->en', 'like', '%'.$search.'%')
                            ->orWhere('id', $search)
                            ->orderBy('gloss')
                            ->limit(250)
                            ->get()
                            ->pluck('lang_name_entries_gloss', 'id')
                            ->toArray();
                    }),

                Repeater::make('extra_data')
                    ->relationship('extra_data')
                    ->schema([
                        TextInput::make('key')->required(),
                        Textarea::make('value')->required(),
                    ])
                    ->defaultItems(0)
                    ->columns(2)
                    ->helperText("'Extra Data' is freeform info that may vary between lexicons.")
                    ->columnSpanFull(),

            ]);
    }
}
