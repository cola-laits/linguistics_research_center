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
use App\Models\LexPartOfSpeech;
use App\Models\LexReflex;
use App\Models\LexReflexExtraData;
use App\Models\LexReflexPartOfSpeech;
use App\Models\LexSemanticCategory;
use App\Models\LexSemanticField;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportMayalexCSV extends Command
{
    protected $lang_ids_lookup = [];
    protected $lexicon_id;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-mayalex-csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Mayalex data from CSVs';

    /**
     * Execute the console command.
     * @throws \Throwable
     */
    public function handle()
    {
        $etymonExtraDataKeys = [
            'kaufman_spelling'=>'word (source spelling)',
            'practical_orthography'=>'word (practical orthography)',
            'ipa_spelling'=>'word (ipa)',
            'spanish_definition' => 'spanish definition',
            'english_definition' => 'english definition',
            'source' => 'source',
            'page_number' => 'page number',
            'full_original_entry' => 'full original entry',
            'alternate_forms' => 'alternate forms',
            'editors' => 'editors',
            'other' => 'other',
        ];
        $reflexExtraDataKeys = [
            'kaufman_spelling'=>'word (source spelling)',
            'practical_orthography'=>'word (practical orthography)',
            'ipa_spelling'=>'word (ipa)',
            'spanish_definition' => 'spanish definition',
            'english_definition' => 'english definition',
            'part_of_speech' => 'part of speech',
            'source' => 'source',
            'page_number' => 'page number',
            'full_original_entry' => 'full original entry',
            'alternate_forms' => 'alternate forms',
            'editors' => 'editors',
            'other' => 'other',
        ];

        $this->info('>> Beginning import');

        //\DB::beginTransaction();

        $import_date = date('Ymd_His');
        $lex = LexLexicon::create([
            'slug' => 'mayalex_' . $import_date,
            'name' => 'MayaLex ' . $import_date,
            'protolang_name' => "Proto-Mayan",
            'viewer_lang_options' => 'en, es',
        ]);
        $this->lexicon_id = $lex->id;

        $this->info('>> Importing languages');

        // create language family and subfamily
        $langs_csv = Reader::createFromPath('app/Console/Commands/import_data/Mayalex languages.csv', 'r');
        $langs_csv->setHeaderOffset(0);
        $langs = $langs_csv->getRecords();
        foreach ($langs as $lang) {
            $lang_id = $this->createMissingLang($lang['Language'], $lang['Family'], $lang['Subfamily']);
            $this->lang_ids_lookup[$lang['Language']] = $lang_id;
        }

        $this->info('>> Importing semantic categories');

        // copy semantic categories
        $categories_csv = Reader::createFromPath('app/Console/Commands/import_data/buck_semantic_category.csv', 'r');
        $categories_csv->setHeaderOffset(0);
        $categories = $categories_csv->getRecords();
        foreach ($categories as $category) {
            LexSemanticCategory::updateOrCreate([
                'lexicon_id' => $this->lexicon_id,
                'abbr' => $category['abbr'],
                'number' => $category['number'],
            ], [
                'text' => $category['text'],
            ]);
        }

        // copy semantic fields
        $fields_csv = Reader::createFromPath('app/Console/Commands/import_data/buck_semantic_field.csv', 'r');
        $fields_csv->setHeaderOffset(0);
        $fields = $fields_csv->getRecords();
        $field_map = [];
        foreach ($fields as $field) {
            if (!$field['abbr']) {
                continue;
            }
            $abbr = $field['abbr'];
            [$category_abbr, $field_abbr] = explode('_', $field['abbr']);
            if ($category_abbr == 'None') {
                continue;
            }
            $category = LexSemanticCategory::where('lexicon_id', $this->lexicon_id)
                ->where('abbr', $category_abbr)
                ->first();
            $field_db = LexSemanticField::updateOrCreate([
                'semantic_category_id' => $category->id,
                'abbr' => $field['abbr'],
                'number' => $field['number'],
            ], [
                'text' => $field['text'],
            ]);
            $field_map[$field['abbr']] = $field_db->id;
        }

        // copy parts of speech
        $this->info('>> Importing parts of speech');
        $poses_csv = Reader::createFromPath('app/Console/Commands/import_data/Mayalex Kaufman_partofspeech_lookup.csv', 'r');
        $poses_csv->setHeaderOffset(0);
        $poses = $poses_csv->getRecords();
        foreach ($poses as $pos_entry) {
            $pos = LexPartOfSpeech::updateOrCreate([
                'lexicon_id' => $this->lexicon_id,
                'code' => trim($pos_entry['Kaufman part of speech']),
            ], [
                'display' => ['en'=>$pos_entry['english'], 'es'=>$pos_entry['spanish']],
            ]);
        }

        // copy kaufman
        $kaufman_csv = Reader::createFromPath('app/Console/Commands/import_data/Mayalex Kaufman.csv', 'r');
        $kaufman_csv->setHeaderOffset(0);
        $kaufman = $kaufman_csv->getRecords();
        $etyma_map = [];
        $this->info(">> Importing Kaufman etyma");
        $bar = $this->output->createProgressBar(count($kaufman_csv));
        // copy kaufman etyma
        foreach ($kaufman as $entry) {
            if ($entry['etymon id']) {
                $bar->advance();
                continue;
            }

            $etyma = LexEtyma::updateOrCreate([
                'lexicon_id' => $this->lexicon_id,
                'entry' => $entry['word (practical orthography)'],
            ], [
                'page_number' => $entry['page number'],
                'gloss' => ['en'=>$entry['english definition'], 'es'=>$entry['spanish definition']],
                'order' => 1,
            ]);
            $etyma_map[$entry['id']] = $etyma->id;

            $semantic_tag = $entry['semantic tag'];
            if (array_key_exists($semantic_tag, $field_map)) {
                LexEtymaSemanticField::updateOrCreate([
                    'etyma_id' => $etyma->id,
                    'semantic_field_id' => $field_map[$semantic_tag],
                ]);
            }

            foreach ($etymonExtraDataKeys as $ed_key=>$ed_value) {
                LexEtymaExtraData::updateOrCreate([
                    'etyma_id' => $etyma->id,
                    'key' => $ed_key,
                ], [
                    'value' => $entry[$ed_value],
                ]);
            }
            $bar->advance();
        }
        $bar->finish();
        $this->info("done!");

        // copy kaufman reflexes
        $this->info(">> Importing Kaufman reflexes");
        $bar = $this->output->createProgressBar(count($kaufman_csv));
        foreach ($kaufman as $entry) {
            if (!$entry['etymon id']) {
                $bar->advance();
                continue;
            }

            $language_id = $this->createMissingLang($entry['language'], 'Other', 'Other');
            $entries_0 = new \stdClass();
            $entries_0->text = $entry['word (practical orthography)'];
            $reflex = new LexReflex();
            $reflex->language_id = $language_id;
            $reflex->gloss = ['en'=>$entry['english definition'], 'es'=>$entry['spanish definition']];
            $reflex->entries = [$entries_0];
            $reflex->save();
            if ($entry['etymon id']) {
                if (!array_key_exists($entry['etymon id'], $etyma_map)) {
                    $this->warn('missing etyma id '.$entry['etymon id']);
                    continue;
                }
                LexEtymaReflex::updateOrCreate([
                    'etyma_id' => $etyma_map[$entry['etymon id']],
                    'reflex_id' => $reflex->id,
                ]);
            }
            if ($entry['part of speech']) {
                LexReflexPartOfSpeech::updateOrCreate([
                    'reflex_id' => $reflex->id,
                    'text' => $entry['part of speech'],
                    'order' => 1,
                ]);
            }
            foreach ($reflexExtraDataKeys as $ed_key=>$ed_value) {
                LexReflexExtraData::updateOrCreate([
                    'reflex_id' => $reflex->id,
                    'key' => $ed_key,
                ], [
                    'value' => $entry[$ed_value],
                ]);
            }
            $bar->advance();
        }
        $bar->finish();
        $this->info("done!");

        $import_lang_files = [
            'Mayalex Cholti.csv',
            'Mayalex Kaqchikel.csv',
            'Mayalex Kiche.csv',
            'Mayalex Yutatek.csv',
        ];
        foreach ($import_lang_files as $lang_file) {
            $this->info('>> Importing '.$lang_file);
            $lang_csv = Reader::createFromPath("app/Console/Commands/import_data/".$lang_file, 'r');
            $lang_csv->setHeaderOffset(0);
            $entries = $lang_csv->getRecords();
            $bar = $this->output->createProgressBar(count($lang_csv));
            foreach ($entries as $entry) {
                $language_id = $this->createMissingLang($entry['language'], 'Other', 'Other');
                $entries_0 = new \stdClass();
                $entries_0->text = $entry['word (practical orthography)'];
                $reflex = new LexReflex();
                $reflex->language_id = $language_id;
                $reflex->gloss = ['en'=>$entry['english definition'], 'es'=>$entry['spanish definition']];
                $reflex->entries = [$entries_0];
                $reflex->save();
                if ($entry['etymon id']) {
                    LexEtymaReflex::updateOrCreate([
                        'etyma_id' => $etyma_map[$entry['etymon id']],
                        'reflex_id' => $reflex->id,
                    ]);
                }
                if ($entry['part of speech']) {
                    LexReflexPartOfSpeech::updateOrCreate([
                        'reflex_id' => $reflex->id,
                        'text' => $entry['part of speech'],
                        'order' => 1,
                    ]);
                }
                foreach ($reflexExtraDataKeys as $ed_key => $ed_value) {
                    LexReflexExtraData::updateOrCreate([
                        'reflex_id' => $reflex->id,
                        'key' => $ed_key,
                    ], [
                        'value' => $entry[$ed_value],
                    ]);
                }
                $bar->advance();
            }
            $bar->finish();
            $this->info("done!");
        }

        //\DB::commit();
        $this->info(">> Successfully created Mayalex ".$import_date);
    }

    protected function createMissingLang($lang_name, $family_name, $subfamily_name): string
    {
        if (array_key_exists($lang_name, $this->lang_ids_lookup)) {
            return $this->lang_ids_lookup[$lang_name];
        }
        $this->warn('creating missing language: '.$lang_name);
        $missing_family = LexLanguageFamily::whereRaw("JSON_EXTRACT(name, '$.en') = ?", $family_name)
            ->where('lexicon_id', $this->lexicon_id)
            ->first();
        if (!$missing_family) {
            $this->warn('creating missing family: '.$family_name);
            $missing_family = LexLanguageFamily::create([
                'lexicon_id' => $this->lexicon_id,
                'name' => $family_name,
                'order' => '1',
            ]);
        }
        $missing_subfamily = LexLanguageSubFamily::whereRaw("JSON_EXTRACT(name, '$.en') = ?", $subfamily_name)
            ->where('family_id', $missing_family->id)
            ->first();
        if (!$missing_subfamily) {
            $this->warn('creating missing subfamily: '.$subfamily_name);
            $missing_subfamily = LexLanguageSubFamily::create([
                'family_id' => $missing_family->id,
                'name' => $subfamily_name,
                'order' => '1',
            ]);
        }
        $missing_lang = LexLanguage::whereRaw("JSON_EXTRACT(name, '$.en') = ?", $lang_name)
            ->where('sub_family_id', $missing_subfamily->id)
            ->first();
        if (!$missing_lang) {
            $this->warn('creating missing lang: '.$lang_name);
            $missing_lang = LexLanguage::create([
                'sub_family_id' => $missing_subfamily->id,
                'name' => $lang_name,
                'order' => 1,
            ]);
        }
        $this->lang_ids_lookup[$lang_name] = $missing_lang->id;
        return $missing_lang->id;
    }
}
