<?php

namespace App\Console\Commands;

use App\Models\LexEtyma;
use App\Models\LexEtymaExtraData;
use App\Models\LexEtymaReflex;
use App\Models\LexEtymaSemanticField;
use App\Models\LexLanguage;
use App\Models\LexPartOfSpeech;
use App\Models\LexReflex;
use App\Models\LexReflexExtraData;
use App\Models\LexReflexPartOfSpeech;
use App\Models\LexSemanticCategory;
use App\Models\LexSemanticField;
use Illuminate\Console\Command;

class ImportProtoSemiticCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lrc:protosemitic_import {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import CSV of Proto-semitic data (temporary 2022)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $semitic_lexicon_id = 2;

        $this->copySemantic($semitic_lexicon_id);

        \DB::transaction(function() use ($semitic_lexicon_id) {

        // do the languages exist?
        $iso_lang_codes = ['Akkadian'=>'akk', 'Syriac'=>'syr', 'Ethiopic'=>'gez', 'Hebrew'=>'he', 'Arabic'=>'ar'];
        $db_lang_ids = [];
        foreach (LexLanguage::all() as $lang) {
            $db_lang_ids[$lang->name] = $lang->id;
        }
        foreach (['Akkadian', 'Syriac', 'Ethiopic', 'Hebrew', 'Arabic'] as $lang) {
            if (!isset($db_lang_ids[$lang])) {
                die("Can't find lang ".$lang);
            }
        }

        // get the CSV data and massage a few headers
        $path = $this->argument("path");
        if (!file_exists($path)) {
            die("No file at ". $path);
        }
        $file_is_verbs = str_contains($path, 'verbals');
        $file_is_nouns = str_contains($path, 'nominals');
        if ($file_is_nouns) {
            // ok
        } else if ($file_is_verbs) {
            // ok
        } else {
            die("Can't tell if nouns or verbs");
        }
        $handle = fopen($path, "r");
        $headers = collect(fgetcsv($handle, 10000, ","))
            // clean up differences in header names between csvs
            ->map(function($header) {
                if ($header == 'pS root') {
                    return 'root';
                }
                $header = strtolower($header);
                // remove parentheticals
                $header = preg_replace('/\s*\([^)]*\)/', '', $header);
                // replace spaces with underscores
                $header = str_replace(' ', '_', $header);
                $header = trim($header);
                return $header;
            });
        $contents = collect();
        while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
            $item = [];
            for ($c=0; $c < count($data); $c++) {
                $item[$headers[$c]] = $data[$c];
            }
            $contents->push($item);
        }

        // Split CSV data into etyma and reflex batches, then process them in that order
        $contents = $contents->filter(fn ($row) => $row['id']);
        $etyma = $contents->filter(fn($value) => str_contains($value['id'], 'ETYMON'));
        $reflexes = $contents->filter(fn($value) => !str_contains($value['id'], 'ETYMON'));
        $etyma_dbids = [];

        // store the etyma
        $order = 0;
        foreach ($etyma as $csv_row) {
            $order += 10;
            $lex_etymon = new LexEtyma();
            $lex_etymon->order = $order;
            if ($file_is_nouns) {
                $lex_etymon->entry = $csv_row['etymon'];
                $lex_etymon->gloss = $csv_row['meaning'];
            } else if ($file_is_verbs) {
                $lex_etymon->entry = $csv_row['etymon'];
                $lex_etymon->gloss = $csv_row['meaning'];
            }
            if (!$lex_etymon->entry) {
                $lex_etymon->entry = 'No text for '.$csv_row['id'];
            }
            if (!$lex_etymon->gloss) {
                $lex_etymon->gloss = 'No meaning/gloss for '.$csv_row['id'];
            }

            $lex_etymon->lexicon_id = $semitic_lexicon_id;
            $etymon_id = $csv_row['id'];
            $extra_data = new \stdClass;
            foreach ($csv_row as $name=>$value) {
                if (in_array($name, ['id', 'etymon_id', 'etymon', 'root', 'meaning', 'language', 'semantic_tag'])) {
                    continue;
                }
                if (trim($value)==="") {
                    continue;
                }
                $extra_data->{$name} = $value;
            }
            $lex_etymon->save();
            foreach ($extra_data as $k=>$v) {
                $ext = new LexEtymaExtraData();
                $ext->key = $k;
                $ext->value = $v;
                $ext->etyma_id = $lex_etymon->id;
                $ext->save();
            }
            $etyma_dbids[$etymon_id] = $lex_etymon->id;

            $semantic_fields = \DB::select(
                'select field.id from lex_semantic_field as field, lex_semantic_category as category'
                . ' where field.semantic_category_id=category.id and category.lexicon_id=?'
                . ' and field.abbr=?',
                [$semitic_lexicon_id, $csv_row['semantic_tag']]
            );
            if (count($semantic_fields)>0) {
                $lex_sem = new LexEtymaSemanticField();
                $lex_sem->etyma_id = $lex_etymon->id;
                $lex_sem->semantic_field_id = $semantic_fields[0]->id;
                $lex_sem->save();
            }

            \Log::info($csv_row['id'].' gloss: '.$lex_etymon->gloss.' | '.$lex_etymon->entry);
        }

        // store the reflexes
        foreach ($reflexes as $csv_row) {
            $csv_row['etymon_id'] = str_replace('?','',$csv_row['etymon_id']);
            $lex_reflex = new LexReflex();
            $lex_reflex->language_id = $db_lang_ids[$csv_row['language']];
            $lex_reflex->lang_attribute = $iso_lang_codes[$csv_row['language']];
            if ($file_is_nouns) {
                $entry = $this->getReflexEntryNoun($csv_row);
                $lex_reflex->gloss = $csv_row['meaning'];
            } else if ($file_is_verbs) {
                $entry = $this->getReflexEntryVerb($csv_row);
                $lex_reflex->gloss = $csv_row['meaning'];
            }
            if (!$entry) {
                $entry = 'No text for '.$csv_row['id'];
            }
            if (!$lex_etymon->gloss) {
                $lex_etymon->gloss = 'No meaning/gloss for '.$csv_row['id'];
            }
            $entries = array(['text'=>$entry]);
            $lex_reflex->entries = $entries;
            $extra_data = new \stdClass;
            foreach ($csv_row as $name=>$value) {
                if (in_array($name, ['id', 'etymon_id', 'etymon', 'root', 'meaning', 'language', 'part_of_speech', 'semantic_tag'])) {
                    continue;
                }
                if (trim($value)==="") {
                    continue;
                }
                $extra_data->{$name} = $value;
            }
            $lex_reflex->save();
            foreach ($extra_data as $k=>$v) {
                $ext = new LexReflexExtraData();
                $ext->key = $k;
                $ext->value = $v;
                $ext->reflex_id = $lex_reflex->id;
                $ext->save();
            }
            if ($csv_row['part_of_speech']) {
                $pos_text = strtolower($csv_row['part_of_speech']);
                $lexpos = LexPartOfSpeech::where('lexicon_id', $semitic_lexicon_id)
                    ->where('code', $pos_text)
                    ->first();
                if (!$lexpos) {
                    $lexpos = new LexPartOfSpeech();
                    $lexpos->lexicon_id = $semitic_lexicon_id;
                    $lexpos->code = $pos_text;
                    $lexpos->display = $pos_text;
                    $lexpos->save();
                }
                $lrpos = new LexReflexPartOfSpeech();
                $lrpos->reflex_id = $lex_reflex->id;
                $lrpos->text = $pos_text;
                $lrpos->order = 10;
                $lrpos->save();
            }
            if ($csv_row['etymon_id']) {
                $lex_etyma_reflex = new LexEtymaReflex();
                $lex_etyma_reflex->reflex_id = $lex_reflex->id;
                $lex_etyma_reflex->etyma_id = $etyma_dbids[$csv_row['etymon_id']];
                $lex_etyma_reflex->save();
            }

            \Log::info($csv_row['id'].' gloss: '.$lex_reflex->gloss.' | '.json_encode($lex_reflex->entries));
        }

        });
    }

    protected function copySemantic($lex_id) {
        if (LexSemanticCategory::where('lexicon_id', $lex_id)->first()) {
            return;
        }
        \DB::transaction(function() use ($lex_id) {
            $cats = LexSemanticCategory::all();
            foreach ($cats as $cat) {
                $newcat = $cat->replicate();
                $newcat->lexicon_id = $lex_id;
                $newcat->save();

                foreach ($cat->semantic_fields as $field) {
                    $newfield = $field->replicate();
                    $newfield->semantic_category_id = $newcat->id;
                    $newfield->save();
                }
            }
        });
    }

    // How do I get the reflex entry from the noun spreadsheet?
    protected function getReflexEntryNoun($csv_row) {
        $translit = $csv_row['transliteration'];
        $script = $csv_row['script'];
        $sem_nor = $csv_row['sem_normalization'];
        $entry = $translit;
        if ($script) {
            $entry .= ' (' . $script .')';
        }
        $entry = trim($entry);
        if (!$entry) {
            $entry = $sem_nor;
        }
        return $entry;
    }

    // How do I get the reflex entry from the verb spreadsheet?
    protected function getReflexEntryVerb($csv_row) {
        $root = $csv_row['root'];
        $root_in_script = $csv_row['root_in_script'];
        $prefix_conj = $csv_row['prefix_conj_1'];
        $entry = $root;
        if ($root_in_script) {
            $entry .= ' (' . $root_in_script .')';
        }
        $entry = trim($entry);
        if (!$entry) {
            $entry = $prefix_conj;
        }
        return $entry;
    }
}
