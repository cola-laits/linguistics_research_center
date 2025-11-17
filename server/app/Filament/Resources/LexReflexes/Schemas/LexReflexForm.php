<?php

namespace App\Filament\Resources\LexReflexes\Schemas;

use App\Models\LexLanguage;
use App\Models\LexReflex;
use App\Models\LexSource;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

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
                Select::make('language_id')
                    ->relationship('language', 'name')
                    ->live()
                    ->columnSpanFull(),
                TextInput::make('lang_attribute')
                    ->columnSpanFull(),
                Repeater::make('sources')
                    ->relationship('sources')
                    ->defaultItems(0)
                    ->table([
                        TableColumn::make('Source'),
                        TableColumn::make('Page Number'),
                        TableColumn::make('Original Text'),
                    ])
                    ->schema([
                        Select::make('source_id')
                            ->label('Source')
                            ->options(function (Get $get) {
                                $language = LexLanguage::find($get('../../language'));
                                $lex = $language?->language_sub_family?->language_family?->lexicon;

                                $query = LexSource::query()
                                    ->with('lexicon')
                                    ->orderBy('lexicon_id')
                                    ->orderBy('code');

                                if ($lex) {
                                    $query->where('lexicon_id', $lex->id);
                                }

                                return $query->get()->pluck('lexicon_name_code_title', 'id')->toArray();
                            })
                            ->searchable()
                            ->preload(false)
                            ->required(),
                        TextInput::make('page_number')
                            ->label('Page Number'),
                        TextArea::make('original_text')
                            ->label('Original Text'),
                    ])
                    ->columnSpanFull(),
                Repeater::make('entries')
                    ->defaultItems(0)
                    ->schema([
                        TextInput::make('text')->required()
                    ])
                    ->grid(3)
                    ->columnSpanFull(),
                Repeater::make('parts_of_speech')
                    ->relationship('parts_of_speech')
                    ->defaultItems(0)
                    ->schema([
                        TextInput::make('text')->required(),
                    ])
                    ->orderColumn('order')
                    ->grid(3)
                    ->hint("Use codes, as listed in the Lexicon > Parts of Speech table")
                    ->columnSpanFull(),
                Repeater::make('cross_references')
                    ->label('Cross references')
                    ->relationship('cross_reference_to_pivots')
                    ->defaultItems(0)
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
                            ->hintIcon('heroicon-m-language', tooltip: 'Translatable. Use the locale selector in the upper right to swap.')
                            ->required(),
                    ])
                    ->helperText("If the LexReflex entry you're editing is borrowed or calqued from another language, click here to add its source from another dictionary.")
                    ->columnSpanFull(),
                Repeater::make('extra_data')
                    ->relationship('extra_data')
                    ->defaultItems(0)
                    ->schema([
                        TextInput::make('key')->required(),
                        Textarea::make('value')
                            ->hintIcon('heroicon-m-language', tooltip: 'Translatable. Use the locale selector in the upper right to swap.')
                            ->required(),
                    ])
                    ->columns(2)
                    ->helperText("'Extra Data' is freeform info that may vary between lexicons.")
                    ->columnSpanFull(),
            ]);
    }
}
