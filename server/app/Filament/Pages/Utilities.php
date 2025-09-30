<?php

namespace App\Filament\Pages;

use App\Models\LexLanguage;
use App\Models\LexLanguageFamily;
use App\Models\LexLanguageSubFamily;
use App\Models\LexLexicon;
use App\Models\LexReflex;
use App\Models\LexReflexExtraData;
use App\Models\LexSemanticCategory;
use App\Models\LexSemanticField;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\HtmlString;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\SyntaxError;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use UnitEnum;

class Utilities extends Page implements HasActions
{
    use InteractsWithActions;

    protected static ?string $title = 'Utilities';
    protected static string | UnitEnum | null $navigationGroup = 'General';
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;
    protected static ?string $navigationLabel = 'Utilities';
    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.utilities';


    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('Site Manager') ?? false;
    }

    protected function makeChooseLexiconStep() {
        $lexicon_options = LexLexicon::all()->pluck('name', 'id');
        return Step::make('Choose Lexicon')
            ->schema([
                Select::make('selected_lexicon')
                    ->label('Lexicon')
                    ->options($lexicon_options)
                    ->required()
            ]);
    }

    protected function runLanguageUploadAction(): Action
    {
        $required_csv_headers = ['Family', 'Subfamily', 'Language'];
        return Action::make('upload-languages')
            ->modal()
            ->modalHeading('Upload Language CSV')
            ->modalSubmitActionLabel('Upload')
            ->modalWidth(Width::FiveExtraLarge)
            ->steps([
                $this->makeChooseLexiconStep(),
                Step::make('Upload CSV')
                    ->schema([
                        TextEntry::make('required_fields')->state('Required Columns: ' . implode(', ', $required_csv_headers)),
                        FileUpload::make('csv')
                            ->storeFiles(false)
                            ->acceptedFileTypes(['text/csv'])
                            ->required()
                            ->rules([
                                $this->validateRequiredCsvHeaders($required_csv_headers),
                            ])
                    ]),
                Step::make('Confirm')
                    ->schema([
                        TextEntry::make('confirm')->state(new HtmlString("<p>Please confirm uploading this data.</p>")),
                    ])
            ])
            ->mountUsing(fn (Schema $form) => $form->fill())
            ->action(function (array $data): void {
                \DB::beginTransaction();
                $selected_lexicon_id = $data['selected_lexicon'];
                $csv = Reader::createFromString($data['csv']->get());
                $csv->setHeaderOffset(0);
                $langs = $csv->getRecords();
                foreach ($langs as $lang) {
                    $this->createMissingLang($selected_lexicon_id, $lang['Language'], $lang['Family'], $lang['Subfamily']);
                }
                \DB::commit();
                Notification::make()
                    ->title('Uploaded successfully')
                    ->success()
                    ->send();
            });
    }

    protected function runSemanticsUploadAction(): Action
    {
        $required_category_csv_headers = ['Text', 'Number', 'Abbr'];
        $required_field_csv_headers = ['Text', 'Number', 'Abbr', 'SemanticCategoryAbbr'];
        return Action::make('upload-semantics')
            ->modal()
            ->modalHeading('Upload Semantic CSVs')
            ->modalSubmitActionLabel('Upload')
            ->modalWidth(Width::SevenExtraLarge)
            ->steps([
                $this->makeChooseLexiconStep(),
                Step::make('Upload Semantic Categories CSV')
                    ->schema([
                        TextEntry::make('required_columns')->state('Required Columns: ' . implode(', ', $required_category_csv_headers)),
                        FileUpload::make('categories_csv')
                            ->storeFiles(false)
                            ->acceptedFileTypes(['text/csv'])
                            ->required()
                            ->rules([
                                $this->validateRequiredCsvHeaders($required_category_csv_headers),
                            ])
                    ]),
                Step::make('Upload Semantic Fields CSV')
                    ->schema([
                        TextEntry::make('required_columns')->state('Required Columns: ' . implode(', ', $required_field_csv_headers)),
                        FileUpload::make('fields_csv')
                            ->storeFiles(false)
                            ->acceptedFileTypes(['text/csv'])
                            ->required()
                            ->rules([
                                $this->validateRequiredCsvHeaders($required_field_csv_headers),
                                fn (Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $fields_csv = Reader::createFromString($get('fields_csv')->get());
                                    $fields_csv->setHeaderOffset(0);
                                    $field_data = $fields_csv->getRecords();

                                    $cats_csv = Reader::createFromString($get('categories_csv')->get());
                                    $cats_csv->setHeaderOffset(0);
                                    $category_data = $cats_csv->getRecords();

                                    $actual_category_abbrs = collect($category_data)->pluck('Abbr')->unique();
                                    $found_category_abbrs = collect($field_data)->pluck('SemanticCategoryAbbr')->unique();
                                    $wrong_category_abbrs = $found_category_abbrs->diff($actual_category_abbrs);
                                    if ($wrong_category_abbrs->isNotEmpty()) {
                                        $fail("Categories in Fields CSV not found in Categories CSV: ".$wrong_category_abbrs->implode(', '));
                                    }
                                },
                            ])
                    ]),
                Step::make('Confirm')
                    ->schema([
                        TextEntry::make('confirm')->state(new HtmlString("<p>Please confirm uploading this data.</p>")),
                    ])
            ])
            ->mountUsing(fn (Schema $form) => $form->fill())
            ->action(function (array $data): void {
                \DB::beginTransaction();
                $selected_lexicon_id = $data['selected_lexicon'];
                $categories_csv = Reader::createFromString($data['categories_csv']->get());
                $categories_csv->setHeaderOffset(0);
                $categories = $categories_csv->getRecords();
                $category_id_map = [];
                foreach ($categories as $category) {
                    $cat = LexSemanticCategory::create([
                        'lexicon_id' => $selected_lexicon_id,
                        'abbr' => $category['Abbr'],
                        'number' => $category['Number'],
                        'text' => $category['Text'],
                    ]);
                    $category_id_map[$category['Abbr']] = $cat->id;
                }

                $fields_csv = Reader::createFromString($data['fields_csv']->get());
                $fields_csv->setHeaderOffset(0);
                $fields = $fields_csv->getRecords();
                foreach ($fields as $field) {
                    LexSemanticField::updateOrCreate([
                        'semantic_category_id' => $category_id_map[$field['SemanticCategoryAbbr']],
                        'abbr' => $field['Abbr'],
                        'number' => $field['Number'],
                        'text' => $field['Text'],
                    ]);
                }
                \DB::commit();

                Notification::make()
                    ->title('Uploaded successfully')
                    ->success()
                    ->send();
            });
    }

    protected function runReflexesUploadAction(): Action
    {
        $required_csv_headers = ['Headwords', 'Gloss'];
        return Action::make('upload-reflexes')
            ->modal()
            ->modalHeading('Upload Reflex CSV')
            ->modalSubmitActionLabel('Upload')
            ->modalWidth(Width::FiveExtraLarge)
            ->steps([
                $this->makeChooseLexiconStep(),
                Step::make('Upload CSV')
                    ->schema([
                        TextEntry::make('required_fields')
                            ->label('Required Columns')
                            ->state(new HtmlString('<ul><li>• Headwords (comma-separated if multiple)</li><li>• Gloss (English assumed; add "Gloss.es" column for Spanish)</li><li>• Language (name)</li></ul>')),
                        TextEntry::make('optional_fields')
                            ->label('Optional Columns')
                            ->state(new HtmlString('<ul><li>• Etyma</li><li>• HomographNumber (if multiple etyma with same spelling)</li><li>• everything else placed in "extra data"</li></ul>')),
                        FileUpload::make('reflexes_csv')
                            ->storeFiles(false)
                            ->acceptedFileTypes(['text/csv'])
                            ->required()
                            ->maxSize(512000) // 500 MB
                            ->rules([
                                $this->validateRequiredCsvHeaders($required_csv_headers),
                            ])
                    ]),
                Step::make('Confirm')
                    ->schema([
                        TextEntry::make('confirm')->state(new HtmlString("<p>Please confirm uploading this data.</p>")),
                    ])
            ])
            ->mountUsing(fn (Schema $form) => $form->fill())
            ->action(function (array $data): void {
                $upload_ctr = 0;
                \DB::beginTransaction();
                $selected_lexicon_id = $data['selected_lexicon'];
                $csv = Reader::createFromString($data['reflexes_csv']->get());
                $csv->setHeaderOffset(0);
                $rows = $csv->getRecords();
                foreach ($rows as $row) {
                    $this->createMissingReflex($selected_lexicon_id, $row);
                    $upload_ctr++;
                    if ($upload_ctr % 100 == 0) {
                        \Log::info("Uploaded " . $upload_ctr);
                    }
                }
                \DB::commit();

                Notification::make()
                    ->title('Uploaded successfully')
                    ->success()
                    ->send();
            });
    }


    /**
     * @param array $required_category_csv_headers
     * @return \Closure
     */
    protected function validateRequiredCsvHeaders(array $required_category_csv_headers): \Closure
    {
        return fn(): \Closure => function (string $attribute, $value, \Closure $fail) use ($required_category_csv_headers) {
            if (!$value instanceof TemporaryUploadedFile) {
                $fail('Please upload a valid CSV file.');
                return;
            }
            try {
                $this->validateUploadedCsv($value, $required_category_csv_headers);
            } catch (\Throwable $e) {
                $fail($e->getMessage());
            }
        };
    }

    /**
     * @throws FileNotFoundException
     * @throws SyntaxError
     * @throws Exception
     */
    private function validateUploadedCsv(TemporaryUploadedFile $file, $required_headers): \Iterator
    {
        $csv = Reader::createFromString($file->get());
        $csv->setHeaderOffset(0);
        $headers = $csv->getHeader();
        foreach ($required_headers as $required_header) {
            if (!in_array($required_header, $headers)) {
                throw new \League\Csv\Exception("Header '{$required_header}' is missing");
            }
        }
        return $csv->getRecords();
    }

    protected function createMissingLang($lexicon_id, $lang_name, $family_name, $subfamily_name): void
    {
        if (!$subfamily_name) {
            $subfamily_name = $family_name;
        }
        $lang_name = trim($lang_name);
        $family_name = trim($family_name);
        $subfamily_name = trim($subfamily_name);

        $family = LexLanguageFamily::create([
            'lexicon_id' => $lexicon_id,
            'name' => $family_name,
            'order' => '1',
        ]);


        $subfamily = LexLanguageSubFamily::create([
            'family_id' => $family->id,
            'name' => $subfamily_name,
            'order' => '1',
        ]);

        $language = LexLanguage::create([
            'sub_family_id' => $subfamily->id,
            'name' => $lang_name,
            'order' => 1,
        ]);
    }

    protected function createMissingReflex($lexicon_id, $row): void
    {
        // fields in $row:
        // * Headwords (comma-separated, goes to 'entries' in LexReflex)
        // * Gloss (assuming English; columns 'Gloss.es' for Spanish
        // * Language (name of LexLanguage)
        // * (Optional) Etyma and HomographNumber (for building Reflex->Etyma linkage)
        // * everything else gets put in LexReflexExtraData

        $reflex = new LexReflex();
        $extra_data = [];
        foreach ($row as $key=>$value) {
            if ($key == 'Headwords') {
                $headword_split = explode(',', $value);
                $headwords = [];
                foreach ($headword_split as $hw) {
                    $entry = (object)['text'=>trim($hw)];
                    $headwords []= $entry;
                }
                $reflex->entries = $headwords;
            } else if ($key == 'Gloss') {
                $reflex->gloss = $value;
            } else if ($key == 'Gloss.es') {
                // FIXME
                // Test - is this right?? $reflex->setTransalation('gloss', 'es', $value);
                throw new \Exception("Non-English glosses not supported yet");
            } else if ($key == 'Language') {
                $lang = $this->getLanguageByNameAndLexiconId($value, $lexicon_id);
                if (!$lang) {
                    throw new Exception('Unknown Language: ' . $value);
                }
                $reflex->language_id = $lang->id;
            } else if ($key == 'Etyma') {
                // FIXME
                throw new \Exception("Etyma crosslinking not supported yet");
            } else if ($key == 'HomographNumber') {
                // FIXME
                throw new \Exception("Etyma crosslinking not supported yet");
            } else {
                if ($value) {
                    $extra_data[$key] = $value;
                }
            }
        }
        $reflex->save();
        foreach ($extra_data as $key=>$val) {
            $ex = new LexReflexExtraData(['key'=>$key, 'value'=>$val]);
            $reflex->extra_data()->save($ex);
        }
    }

    protected array $cachedLanguages = [];
    protected function getLanguageByNameAndLexiconId($name, $lexicon_id): ?LexLanguage {
        $cache_key = "language-{$name}-{$lexicon_id}";
        if (!array_key_exists($cache_key, $this->cachedLanguages)) {
            $this->cachedLanguages[$cache_key] = LexLanguage::query()
                ->whereHas('language_sub_family.language_family', function ($q) use ($lexicon_id) {
                    $q->where('lexicon_id', $lexicon_id);
                })
                ->where('name->en', $name)
                ->first();
        }
        return $this->cachedLanguages[$cache_key];
    }
}
