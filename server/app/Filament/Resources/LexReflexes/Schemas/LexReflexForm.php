<?php

namespace App\Filament\Resources\LexReflexes\Schemas;

use App\Models\LexReflex;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LexReflexForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id')
                    ->readOnly()
                    ->disabled()
                    ->columnSpanFull(),
                TextInput::make('gloss')
                    ->hintIcon('heroicon-m-language', tooltip: 'Translatable. Use the locale selector in the upper right to swap.')
                    ->columnSpanFull(),
                Select::make('language')
                    ->relationship('language', 'name')
                    ->columnSpanFull(),
                TextInput::make('lang_attribute')
                    ->columnSpanFull(),
                Select::make('sources')
                    ->multiple()
                    ->relationship('sources', 'name')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->lexiconNameCode)
                    ->columnSpanFull(),
                Repeater::make('entries')
                    ->schema([
                        TextInput::make('text')->required()
                    ])
                    ->grid(3)
                    ->columnSpanFull(),
                Repeater::make('parts_of_speech')
                    ->relationship('parts_of_speech')
                    ->schema([
                        TextInput::make('text')->required(),
                    ])
                    ->orderColumn('order')
                    ->grid(3)
                    ->hint("Use codes, as listed in the Lexicon > Parts of Speech table")
                    ->columnSpanFull(),
                Repeater::make('cross_references')
                    ->relationship('cross_reference_to_pivots')
                    ->schema([
                        Select::make('from_reflex_id')
                            ->relationship('from_reflex', 'langNameEntriesGloss')
                            ->preload(false)
                            ->searchable()
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
                            })
                            ->helperText('the current entry comes from...')
                            ->required(),
                        TextInput::make('relationship')
                            ->helperText('e.g. borrowing, calque, ...')
                            ->required(),
                    ])
                    ->helperText("If the LexReflex entry you're editing is borrowed or calqued from another language, click here to add its source from another dictionary.")
                    ->columnSpanFull(),
                Repeater::make('extra_data')
                    ->relationship('extra_data')
                    ->schema([
                        TextInput::make('key')->required(),
                        Textarea::make('value')->required(),
                    ])
                    ->columns(2)
                    ->helperText("'Extra Data' is freeform info that may vary between lexicons.")
                    ->columnSpanFull(),
            ]);
    }
}
