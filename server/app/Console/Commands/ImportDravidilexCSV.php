<?php

namespace App\Console\Commands;

use App\Models\LexEtyma;
use App\Models\LexEtymaExtraData;
use App\Models\LexEtymaReflex;
use App\Models\LexEtymaSemanticField;
use App\Models\LexLanguage;
use App\Models\LexLanguageFamily;
use App\Models\LexLanguageSubFamily;
use App\Models\LexLexicon;
use App\Models\LexReflex;
use App\Models\LexReflexExtraData;
use App\Models\LexSemanticCategory;
use App\Models\LexSemanticField;
use App\Models\LexSource;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class ImportDravidilexCSV extends Command
{
    protected const DATA_DIR = 'app/Console/Commands/import_data/dravidilex/';

    /** JSON columns consumed directly for the reflex/etymon record itself. */
    protected const JSON_CORE_KEYS = [
        'Headwords',
        'HeadwordEntries',
        'EtymonEntry',
        'Gloss',
        'Language',
    ];

    /** JSON marker/link columns consumed for etyma linking. */
    protected const JSON_LINK_KEYS = ['IsEtymon', 'HomographNumber', 'Etyma', 'EtymaHomographNumber'];

    /** JSON reflex fields represented by the Starling source citation. */
    protected const JSON_SOURCE_CITATION_KEYS = ['Starling ID', 'URL'];

    /** JSON root fields consumed by the import command, not public Other Info. */
    protected const JSON_ETYMON_IMPORT_ONLY_KEYS = ['Semantic Tag (Buck)', 'Semantic Field (Buck)'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-dravidilex-csv {reflexes_json? : Path to dravidilex_batch_import.json; when given, etyma + reflexes are imported too}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the DravidiLex lexicon from CSVs and import Starling etyma and reflexes from JSON.';

    protected int $lexicon_id;

    /** Language name => LexLanguage id. */
    protected array $lang_ids_lookup = [];

    /** Buck field abbr (e.g. "PA_MD") => LexSemanticField id. */
    protected array $field_map = [];

    /** Source code (e.g. "DEDR") => LexSource id. */
    protected array $sources_by_code = [];

    /**
     * Execute the console command.
     * @throws \Throwable
     */
    public function handle(): int
    {
        $slug = 'dravidilex_pilot';
        if (LexLexicon::where('slug', $slug)->exists()) {
            $this->error("A lexicon with slug '{$slug}' already exists. Delete it before re-running this import.");
            return self::FAILURE;
        }

        $this->info('>> Beginning DravidiLex import');
        DB::beginTransaction();
        try {
            $lex = LexLexicon::create([
                'slug' => $slug,
                'name' => 'DravidiLex Pilot',
                'protolang_name' => 'Proto-Dravidian',
                'viewer_lang_options' => 'en',
            ]);
            $this->lexicon_id = $lex->id;

            $this->importLanguages();
            $this->importSemanticFields();

            if ($this->argument('reflexes_json')) {
                $this->importReflexes($this->argument('reflexes_json'));
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Import rolled back: ' . $e->getMessage());
            throw $e;
        }

        $this->newLine();
        $this->info('>> Successfully created lexicon "' . $slug . '" (id ' . $lex->id . ')');
        if (!$this->argument('reflexes_json')) {
            $this->info('   Next: re-run with the path to dravidilex_batch_import.json to import etyma + reflexes.');
        }
        $this->info('   Then run: php artisan app:generate-lexicon-data-cache ' . $lex->id);
        return self::SUCCESS;
    }

    /**
     * Build the three-tier language tree (family -> subfamily -> language) from
     * the committed CSV. Branch proto-languages are stored as ordinary
     * LexLanguage rows so reconstructed reflexes can attach; top-level
     * Proto-Dravidian stays on the built-in protolanguage page.
     */
    protected function importLanguages(): void
    {
        $this->info('>> Importing languages (three tiers)');

        $langs_csv = Reader::createFromPath(self::DATA_DIR . 'Dravidilex_Languages.csv', 'r');
        $langs_csv->setHeaderOffset(0);

        $families = [];
        $subfamilies = [];
        $family_order = 0;
        $subfamily_order = 0;
        $language_order = 0;

        foreach ($langs_csv->getRecords() as $row) {
            $family_name = trim($row['Family']);
            $subfamily_name = trim($row['Subfamily']) ?: $family_name;
            $lang_name = trim($row['Language']);

            if (!array_key_exists($family_name, $families)) {
                $families[$family_name] = LexLanguageFamily::create([
                    'lexicon_id' => $this->lexicon_id,
                    'name' => $family_name,
                    'order' => ++$family_order,
                ]);
            }
            $family = $families[$family_name];

            $subfamily_key = $family_name . '|' . $subfamily_name;
            if (!array_key_exists($subfamily_key, $subfamilies)) {
                $subfamilies[$subfamily_key] = LexLanguageSubFamily::create([
                    'family_id' => $family->id,
                    'name' => $subfamily_name,
                    'order' => ++$subfamily_order,
                ]);
            }
            $subfamily = $subfamilies[$subfamily_key];

            if (array_key_exists($lang_name, $this->lang_ids_lookup)) {
                $this->warn('duplicate language row skipped: ' . $lang_name);
                continue;
            }
            $language = LexLanguage::create([
                'sub_family_id' => $subfamily->id,
                'name' => $lang_name,
                'order' => ++$language_order,
            ]);
            $this->lang_ids_lookup[$lang_name] = $language->id;
        }
        $this->info('   ' . count($families) . ' families, ' . count($subfamilies) . ' subfamilies, ' . count($this->lang_ids_lookup) . ' languages');
    }

    /**
     * Seed the Buck semantic categories + fields (shared CSVs, same as MayaLex)
     * and remember each field's id so root etyma can be linked to it.
     */
    protected function importSemanticFields(): void
    {
        $this->info('>> Importing Buck semantic categories and fields');

        $categories_csv = Reader::createFromPath('app/Console/Commands/import_data/buck_semantic_category.csv', 'r');
        $categories_csv->setHeaderOffset(0);
        foreach ($categories_csv->getRecords() as $category) {
            LexSemanticCategory::updateOrCreate([
                'lexicon_id' => $this->lexicon_id,
                'abbr' => $category['abbr'],
                'number' => $category['number'],
            ], [
                'text' => $category['text'],
            ]);
        }

        $fields_csv = Reader::createFromPath('app/Console/Commands/import_data/buck_semantic_field.csv', 'r');
        $fields_csv->setHeaderOffset(0);
        foreach ($fields_csv->getRecords() as $field) {
            if (!$field['abbr']) {
                continue;
            }
            [$category_abbr] = explode('_', $field['abbr']);
            if ($category_abbr === 'None') {
                continue;
            }
            $category = LexSemanticCategory::where('lexicon_id', $this->lexicon_id)
                ->where('abbr', $category_abbr)
                ->first();
            if (!$category) {
                $this->warn('no category for semantic field: ' . $field['abbr']);
                continue;
            }
            $field_db = LexSemanticField::updateOrCreate([
                'semantic_category_id' => $category->id,
                'abbr' => $field['abbr'],
                'number' => $field['number'],
            ], [
                'text' => $field['text'],
            ]);
            $this->field_map[$field['abbr']] = $field_db->id;
        }
        $this->info('   ' . count($this->field_map) . ' semantic fields');
    }

    /**
     * Import the flattened Starling records.
     *
     * Two passes so linking is order-independent:
     *   1. every IsEtymon row  -> LexEtyma (+ its Buck semantic-field link).
     *      Etyma cannot hold sources, so their Starling provenance rides as a
     *      "Source (StarlingDB)" extra-data line (stored generically below).
     *   2. every other row     -> LexReflex, linked directly to its root etymon
     *      via the pre-flattened Etyma / EtymaHomographNumber columns, plus its
     *      Sources (DEDR / CVOTGD / STARLING) attached to the lex_reflex_source
     *      pivot.
     */
    protected function importReflexes(string $json_path): void
    {
        if (!is_file($json_path)) {
            throw new \RuntimeException('Reflex JSON not found: ' . $json_path);
        }
        ini_set('memory_limit', '1G'); // source-enriched DravidiLex JSON is large.
        $records = json_decode(file_get_contents($json_path));
        if (!is_array($records)) {
            throw new \RuntimeException('Could not decode reflex JSON: ' . $json_path);
        }

        $roots = [];
        $reflexes = [];
        foreach ($records as $record) {
            if (!empty($record->IsEtymon)) {
                $roots[] = $record;
            } else {
                $reflexes[] = $record;
            }
        }
        $this->info('>> Importing ' . count($roots) . ' etyma and ' . count($reflexes) . ' reflexes');

        $this->seedSources();

        // Starling/DED numbers every root starting at 1, even ones with no
        // actual homograph sibling, so the raw number alone can't drive the
        // "(N)" display — only entries that collide on entry text should show one.
        $entry_counts = [];
        foreach ($roots as $record) {
            $entry = trim((string)($record->EtymonEntry
                ?? str_replace('*', '', (string)($record->Headwords ?? ''))));
            $entry_counts[$entry] = ($entry_counts[$entry] ?? 0) + 1;
        }

        // Pass 1: etyma. Keyed by (entry, homograph number) so reflexes can
        // resolve their root from the Etyma columns.
        $etyma_index = [];
        $this->info('   creating etyma');
        $bar = $this->output->createProgressBar(count($roots));
        foreach ($roots as $record) {
            // New DravidiLex JSON carries the canonical asterisk-free entry.
            // The fallback keeps older generated JSON importable.
            $entry = trim((string)($record->EtymonEntry
                ?? str_replace('*', '', (string)($record->Headwords ?? ''))));
            $homograph = (int)($record->HomographNumber ?? 1) ?: 1;
            // Linking key always resolves (defaults to 1); the stored/displayed
            // number is only set when this entry text actually has a sibling.
            $display_homograph = $entry_counts[$entry] > 1 ? $homograph : null;

            $etymon = LexEtyma::create([
                'lexicon_id' => $this->lexicon_id,
                'entry' => $entry,
                'gloss' => ['en' => $record->Gloss],
                'homograph_number' => $display_homograph,
                'order' => 1,
            ]);
            $etyma_index[$this->etymaKey($entry, $homograph)] = $etymon;

            // This fills the Categories tab and the semantic_tag column.
            $tag = $record->{'Semantic Tag (Buck)'} ?? null;
            if ($tag !== null && array_key_exists($tag, $this->field_map)) {
                LexEtymaSemanticField::updateOrCreate([
                    'etyma_id' => $etymon->id,
                    'semantic_field_id' => $this->field_map[$tag],
                ]);
            } elseif ($tag) {
                $this->warn('unknown Buck tag "' . $tag . '" on ' . $entry);
            }

            $this->saveExtraData($record, $etymon, null);

            $bar->advance();
        }
        $bar->finish();
        $this->newLine();

        // Pass 2: reflexes, each linked directly to its topmost root etymon.
        $this->info('   creating reflexes');
        $unlinked = 0;
        $bar = $this->output->createProgressBar(count($reflexes));
        foreach ($reflexes as $record) {
            $reflex = $this->buildReflex($record);
            $this->saveExtraData($record, null, $reflex);

            $etyma_entry = trim((string)($record->Etyma ?? ''));
            $etyma_homograph = (int)($record->EtymaHomographNumber ?? 1) ?: 1;
            $key = $this->etymaKey($etyma_entry, $etyma_homograph);
            if ($etyma_entry !== '' && array_key_exists($key, $etyma_index)) {
                LexEtymaReflex::create([
                    'etyma_id' => $etyma_index[$key]->id,
                    'reflex_id' => $reflex->id,
                ]);
            } else {
                $unlinked++;
                if ($unlinked <= 20) {
                    $this->warn('no etymon "' . $etyma_entry . '" (homograph ' . $etyma_homograph . ') for ' . ($record->{'Starling ID'} ?? $record->Headwords));
                }
            }
            $this->attachSources($reflex, $record);
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();

        $this->info('   ' . count($etyma_index) . ' etyma created; ' . count($reflexes) . ' reflexes; ' . $unlinked . ' reflexes with no matching etymon');
    }

    /**
     * Create the lexicon's LexSource records from the committed bibliography CSV
     * (code + display), so reflex Sources can resolve their source by code.
     */
    protected function seedSources(): void
    {
        $csv = Reader::createFromPath(self::DATA_DIR . 'Dravidilex_Sources.csv', 'r');
        $csv->setHeaderOffset(0);
        foreach ($csv->getRecords() as $row) {
            $code = trim($row['code'] ?? '');
            if ($code === '') {
                continue;
            }
            $source = LexSource::updateOrCreate(
                ['lexicon_id' => $this->lexicon_id, 'code' => $code],
                ['display' => $row['display'] ?? $code]
            );
            $this->sources_by_code[$code] = $source->id;
        }
        $this->info('   ' . count($this->sources_by_code) . ' sources');
    }

    /**
     * Attach a reflex's Sources (a JSON array of {source, page_number,
     * original_entry}) to the lex_reflex_source pivot.
     */
    protected function attachSources(LexReflex $reflex, object $record): void
    {
        if (!isset($record->Sources) || !is_array($record->Sources)) {
            return;
        }
        foreach ($record->Sources as $src) {
            $code = trim((string)($src->source ?? ''));
            if ($code === '' || !array_key_exists($code, $this->sources_by_code)) {
                $this->warn('unknown source code "' . $code . '" on ' . ($record->{'Starling ID'} ?? $record->Headwords));
                continue;
            }
            $reflex->sources()->attach($this->sources_by_code[$code], [
                'page_number' => (string)($src->page_number ?? ''),
                'original_text' => (string)($src->original_entry ?? ''),
            ]);
        }
    }

    /**
     * Build (and persist) a LexReflex from a record's core columns. New JSON
     * carries pre-shaped HeadwordEntries so commas inside parenthetical
     * morphology stay display text; older JSON falls back to comma-splitting
     * Headwords. The language is resolved (auto-created under an "Other" family
     * if the CSV did not list it).
     */
    protected function buildReflex(object $record): LexReflex
    {
        $reflex = new LexReflex();
        $reflex->language_id = $this->resolveLanguage($record->Language);
        $reflex->gloss = ['en' => $record->Gloss];
        $reflex->entries = array_map(
            fn ($text) => (object)['text' => $text],
            $this->reflexEntryTexts($record)
        );
        $reflex->save();
        return $reflex;
    }

    protected function reflexEntryTexts(object $record): array
    {
        if (isset($record->HeadwordEntries) && is_array($record->HeadwordEntries)) {
            return array_values(array_filter(array_map(function ($entry) {
                if (is_object($entry) && isset($entry->text)) {
                    return trim((string) $entry->text);
                }
                return trim((string) $entry);
            }, $record->HeadwordEntries), fn ($text) => $text !== ''));
        }

        return array_values(array_filter(array_map(
            fn ($text) => trim($text),
            explode(',', (string)($record->Headwords ?? ''))
        ), fn ($text) => $text !== ''));
    }

    /**
     * Persist every non-core, non-link column as extra data on whichever record
     * is passed (exactly one of $etymon / $reflex).
     */
    protected function saveExtraData(object $record, ?LexEtyma $etymon, ?LexReflex $reflex): void
    {
        $skip = array_merge(self::JSON_CORE_KEYS, self::JSON_LINK_KEYS, ['Sources']);
        if ($reflex) {
            $skip = array_merge($skip, self::JSON_SOURCE_CITATION_KEYS);
        }
        if ($etymon) {
            $skip = array_merge($skip, self::JSON_ETYMON_IMPORT_ONLY_KEYS);
        }
        foreach ($record as $key => $value) {
            if (in_array($key, $skip, true) || $value === null || $value === '') {
                continue;
            }
            if ($etymon) {
                LexEtymaExtraData::create([
                    'etyma_id' => $etymon->id,
                    'key' => $key,
                    'value' => $value,
                ]);
            } else {
                LexReflexExtraData::create([
                    'reflex_id' => $reflex->id,
                    'key' => $key,
                    'value' => $value,
                ]);
            }
        }
    }

    /**
     * Resolve a language name to its id, auto-creating any not listed in the CSV
     * under an "Other" family/subfamily (MayaLex behaviour) so the import never
     * hard-fails on a stray language from a future re-scrape.
     */
    protected function resolveLanguage(string $lang_name): int
    {
        $lang_name = trim($lang_name);
        if (array_key_exists($lang_name, $this->lang_ids_lookup)) {
            return $this->lang_ids_lookup[$lang_name];
        }
        $this->warn('creating unlisted language: ' . $lang_name);
        $family = LexLanguageFamily::whereRaw("JSON_EXTRACT(name, '$.en') = ?", 'Other')
            ->where('lexicon_id', $this->lexicon_id)
            ->first();
        if (!$family) {
            $family = LexLanguageFamily::create([
                'lexicon_id' => $this->lexicon_id,
                'name' => 'Other',
                'order' => 999,
            ]);
        }
        $subfamily = LexLanguageSubFamily::whereRaw("JSON_EXTRACT(name, '$.en') = ?", 'Other')
            ->where('family_id', $family->id)
            ->first();
        if (!$subfamily) {
            $subfamily = LexLanguageSubFamily::create([
                'family_id' => $family->id,
                'name' => 'Other',
                'order' => 999,
            ]);
        }
        $language = LexLanguage::create([
            'sub_family_id' => $subfamily->id,
            'name' => $lang_name,
            'order' => 999,
        ]);
        $this->lang_ids_lookup[$lang_name] = $language->id;
        return $language->id;
    }

    protected function etymaKey(string $entry, int $homograph): string
    {
        return $entry . '|' . $homograph;
    }

}
