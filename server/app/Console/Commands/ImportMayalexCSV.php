<?php

namespace App\Console\Commands;

use App\Models\LexEtyma;
use App\Models\LexEtymaExtraData;
use App\Models\LexEtymaReflex;
use App\Models\LexEtymaSemanticField;
use App\Models\LexLanguage;
use App\Models\LexLanguageFamily;
use App\Models\LexLanguageSubFamily;
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
        $this->info('beginning import');

        //\DB::beginTransaction();

        $maya_lexicon_id = 4;

        $this->info('importing languages');

        // create language family and subfamily
        $family = LexLanguageFamily::updateOrCreate([
            'lexicon_id' => $maya_lexicon_id,
            'order' => '1',
        ], [
            'name' => ['en'=>'Mayan', 'es'=>'Maya'],
        ]);
        $subfamily = LexLanguageSubFamily::updateOrCreate([
            'family_id' => $family->id,
            'order' => '1',
        ], [
            'name' => ['en'=>'Mayan', 'es'=>'Mayan'],
        ]);

        $this->info('importing semantic categories');

        // copy semantic categories
        $categories_csv = Reader::createFromPath('app/Console/Commands/Mayalex Kaufman_Semantic_Categories.csv', 'r');
        $categories_csv->setHeaderOffset(0);
        $categories = $categories_csv->getRecords();
        foreach ($categories as $category) {
            LexSemanticCategory::updateOrCreate([
                'lexicon_id' => $maya_lexicon_id,
                'abbr' => $category['Kaufman Abbreviations'],
                'number' => $category['Kaufman Number'],
            ], [
                'text' => $category['Kaufman Categories'],
            ]);
        }

        // copy semantic fields
        $fields_csv = Reader::createFromPath('app/Console/Commands/Mayalex Kaufman_Semantic_Fields.csv', 'r');
        $fields_csv->setHeaderOffset(0);
        $fields = $fields_csv->getRecords();
        $field_map = [];
        foreach ($fields as $field) {
            if (!$field['Abbreviation']) {
                continue;
            }
            $abbr = $field['Abbreviation'];
            [$category_abbr, $junk] = explode('_', $abbr);
            if ($category_abbr == 'None') {
                continue;
            }
            $category = LexSemanticCategory::where('lexicon_id', $maya_lexicon_id)
                ->where('abbr', $category_abbr)
                ->first();
            $field = LexSemanticField::updateOrCreate([
                'semantic_category_id' => $category->id,
                'abbr' => $abbr,
                'number' => $field['Number'],
            ], [
                'text' => $field['Field'],
            ]);
            $field_map[$field['Abbreviation']] = $field->id;
        }

        // copy parts of speech
        $this->info('importing parts of speech');
        $poses_csv = Reader::createFromPath('app/Console/Commands/Mayalex Kaufman_partofspeech_lookup.csv', 'r');
        $poses_csv->setHeaderOffset(0);
        $poses = $poses_csv->getRecords();
        foreach ($poses as $pos_entry) {
            $pos = LexPartOfSpeech::updateOrCreate([
                'lexicon_id' => $maya_lexicon_id,
                'code' => trim($pos_entry['Kaufman part of speech']),
            ], [
                'display' => ['en'=>$pos_entry['english'], 'es'=>$pos_entry['spanish']],
            ]);
        }

        // copy kaufman
        $kaufman_csv = Reader::createFromPath('app/Console/Commands/Mayalex Kaufman_Database.csv', 'r');
        $kaufman_csv->setHeaderOffset(0);
        $kaufman = $kaufman_csv->getRecords();
        $etyma_map = [];
        $kaufman_ctr = 0;
        // copy kaufman etyma
        foreach ($kaufman as $entry) {
            if (strpos($entry['ID'], 'ETYMON') !== 0) {
                continue;
            }
            $this->info('importing kaufman entry '.$kaufman_ctr++);

            $etyma = LexEtyma::updateOrCreate([
                'lexicon_id' => $maya_lexicon_id,
                'entry' => $entry['Headword (practical orthography)'],
            ], [
                'page_number' => $entry['Page in Kaufman\'s file'],
                'gloss' => ['en'=>$entry['English Definition'], 'es'=>$entry['Spanish Definition']],
                'order' => 1,
            ]);
            $etyma_map[$entry['ID']] = $etyma->id;

            $semantic_tag = $entry['Kaufman_Semantic_Tag'];
            if (array_key_exists($semantic_tag, $field_map)) {
                LexEtymaSemanticField::updateOrCreate([
                    'etyma_id' => $etyma->id,
                    'semantic_field_id' => $field_map[$semantic_tag],
                ]);
            }

            $extraDataKeys = [
                'kaufman_spelling'=>'Headword (Kaufman spelling)',
                'practical_orthography'=>'Headword (practical orthography)',
                'definition' => 'Definition',
                'practical_orthography_definition' => 'Definition (practical orthography)',
                'spanish_definition' => 'Spanish Definition',
                'english_definition' => 'English Definition',
                'part_of_speech' => 'Part of Speech',
                'english_part_of_speech' => 'Eng. Part of Speech',
                'spanish_part_of_speech' => 'Spn. Part of Speech',
                'source' => 'Source',
                'editors' => 'Editors',
            ];
            foreach ($extraDataKeys as $ed_key=>$ed_value) {
                LexEtymaExtraData::updateOrCreate([
                    'etyma_id' => $etyma->id,
                    'key' => $ed_key,
                ], [
                    'value' => $entry[$ed_value],
                ]);
            }
        }

        // copy kaufman reflexes
        foreach ($kaufman as $entry) {
            if (strpos($entry['ID'], 'ETYMON') === 0) {
                continue;
            }
            $this->info('importing kaufman entry '.$kaufman_ctr++);

            $language = LexLanguage::updateOrCreate([
                'sub_family_id' => $subfamily->id,
                'abbr' => $entry['Language Abbrv'],
            ], [
                'name' => ['en'=>$entry['Language'], 'es'=>$entry['Language']],
                'order' => 1,
            ]);
            $entries_0 = new \stdClass();
            $entries_0->text = $entry['Headword (practical orthography)'];
            $reflex = LexReflex::updateOrCreate([
                'language_id' => $language->id,
                'gloss' => ['en'=>$entry['English Definition'], 'es'=>$entry['Spanish Definition']],
            ], [
                'entries' => [$entries_0],
            ]);
            if ($entry['Etymon ID']) {
                LexEtymaReflex::updateOrCreate([
                    'etyma_id' => $etyma_map[$entry['Etymon ID']],
                    'reflex_id' => $reflex->id,
                ]);
            }
            if ($entry['Part of Speech']) {
                LexReflexPartOfSpeech::updateOrCreate([
                    'reflex_id' => $reflex->id,
                    'text' => $entry['Part of Speech'],
                    'order' => 1,
                ]);
            }
            $extraDataKeys = [
                'kaufman_spelling'=>'Headword (Kaufman spelling)',
                'practical_orthography'=>'Headword (practical orthography)',
                'definition' => 'Definition',
                'practical_orthography_definition' => 'Definition (practical orthography)',
                'meaning_spanish' => 'Spanish Definition',
                'meaning_english' => 'English Definition',
                'english_part_of_speech' => 'Eng. Part of Speech',
                'spanish_part_of_speech' => 'Spn. Part of Speech',
                'source' => 'Source',
                'editors' => 'Editors',
            ];
            foreach ($extraDataKeys as $ed_key=>$ed_value) {
                LexReflexExtraData::updateOrCreate([
                    'reflex_id' => $reflex->id,
                    'key' => $ed_key,
                ], [
                    'value' => $entry[$ed_value],
                ]);
            }
        }

        // copy kiche
        $this->info('importing kiche');
        $kiche_csv = Reader::createFromPath("app/Console/Commands/Mayalex K'iche' Words Expanded.csv", 'r');
        $kiche_csv->setHeaderOffset(0);
        $kiche = $kiche_csv->getRecords();
        foreach ($kiche as $entry) {
            $language = LexLanguage::updateOrCreate([
                'sub_family_id' => $subfamily->id,
                'abbr' => 'KIC',
            ], [
                'name' => ['en'=>"K'iche'", 'es'=>"K'iche'"],
                'order' => 1,
            ]);
            $entries_0 = new \stdClass();
            $entries_0->text = $entry['Headword (Practical Orthography)'];
            $reflex = LexReflex::updateOrCreate([
                'language_id' => $language->id,
                'gloss' => ['en'=>$entry['Meaning (English)'], 'es'=>$entry['Meaning (Spanish)']],
            ], [
                'entries' => [$entries_0],
            ]);
            if ($entry['Etymon ID']) {
                LexEtymaReflex::updateOrCreate([
                    'etyma_id' => $etyma_map[$entry['Etymon ID']],
                    'reflex_id' => $reflex->id,
                ]);
            }
            if ($entry['Part of Speech']) {
                LexReflexPartOfSpeech::updateOrCreate([
                    'reflex_id' => $reflex->id,
                    'text' => $entry['Part of Speech'],
                    'order' => 1,
                ]);
            }
            $extraDataKeys = [
                'source_spelling'=>'Headword (Source Spelling)',
                'practical_orthography'=>'Headword (Practical Orthography)',
                'headword_ipa'=>'Headword (IPA)',
                'meaning_spanish'=>'Meaning (Spanish)',
                'meaning_english'=>'Meaning (English)',
                'source'=>'Sources',
                'full_original_entry'=>'Full Original Entry',
                'alternate_forms_spellings'=>'Alternate forms/spellings',
                'other'=>'Other',
                'editors'=>'Editors',
            ];
            foreach ($extraDataKeys as $ed_key=>$ed_value) {
                LexReflexExtraData::updateOrCreate([
                    'reflex_id' => $reflex->id,
                    'key' => $ed_key,
                ], [
                    'value' => $entry[$ed_value],
                ]);
            }
        }

        // copy cholti
        $this->info('importing cholti');
        $cholti_csv = Reader::createFromPath("app/Console/Commands/Mayalex Ch'olti'_Database.csv", 'r');
        $cholti_csv->setHeaderOffset(0);
        $cholti = $cholti_csv->getRecords();
        foreach ($cholti as $entry) {
            $language = LexLanguage::updateOrCreate([
                'sub_family_id' => $subfamily->id,
                'abbr' => 'CHO',
            ], [
                'name' => ['en'=>"Ch'olti'", 'es'=>"Ch'olti'"],
                'order' => 1,
            ]);
            $entries_0 = new \stdClass();
            $entries_0->text = $entry['Headword (Practical Orthography)'];
            $reflex = LexReflex::updateOrCreate([
                'language_id' => $language->id,
                'gloss' => ['en'=>$entry['Meaning (English)'], 'es'=>$entry['Meaning (Spanish)']],
            ], [
                'entries' => [$entries_0],
            ]);
            if ($entry['Etymon ID']) {
                LexEtymaReflex::updateOrCreate([
                    'etyma_id' => $etyma_map[$entry['Etymon ID']],
                    'reflex_id' => $reflex->id,
                ]);
            }
            if ($entry['Part of Speech']) {
                LexReflexPartOfSpeech::updateOrCreate([
                    'reflex_id' => $reflex->id,
                    'text' => $entry['Part of Speech'],
                    'order' => 1,
                ]);
            }
            $extraDataKeys = [
                'source_spelling'=>'Headword (Source Spelling)',
                'practical_orthography'=>'Headword (Practical Orthography)',
                'headword_ipa'=>'Headword (IPA)',
                'source_page_number'=>'Manuscript Page Number',
                'meaning_spanish'=>'Meaning (Spanish)',
                'meaning_spanish_unmodernized'=>'Meaning Spanish (unmodernized)',
                'meaning_english'=>'Meaning (English)',
                'source'=>'Sources',
                'full_original_entry'=>'Full Original Entry',
                'alternate_forms_spellings'=>'Alternate forms/spellings',
                'other'=>'Other',
                'editors'=>'Editors',
            ];
            foreach ($extraDataKeys as $ed_key=>$ed_value) {
                LexReflexExtraData::updateOrCreate([
                    'reflex_id' => $reflex->id,
                    'key' => $ed_key,
                ], [
                    'value' => $entry[$ed_value],
                ]);
            }
        }

        //\DB::commit();
    }
}
