<?php

namespace App\Console\Commands;

use App\Models\LexEtyma;
use App\Models\LexEtymaReflex;
use App\Models\LexLanguage;
use App\Models\LexPartOfSpeech;
use App\Models\LexReflex;
use App\Models\LexReflexPartOfSpeech;
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
        \DB::transaction(function() {
        $semitic_lexicon_id = 2;

        // do the languages exist?
        $iso_lang_codes = ['Akkadian'=>'akk', 'Syriac'=>'syr', 'Ethiopic'=>'gez', 'Hebrew'=>'he', 'Arabic'=>'ar'];
        foreach (['Akkadian', 'Syriac', 'Ethiopic', 'Hebrew', 'Arabic'] as $lang) {
            $lang = LexLanguage::where('name', $lang)->first();
            if (!$lang) {
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
                if ($header == 'Etymon ID') {
                    return 'ETYMON ID';
                }
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
        $contents = $contents->filter(fn ($row) => $row['ID']);
        $etyma = $contents->filter(fn($value) => str_contains($value['ID'], 'ETYMON'));
        $reflexes = $contents->filter(fn($value) => !str_contains($value['ID'], 'ETYMON'));
        $etyma_dbids = [];

        // store the etyma
        $order = 0;
        foreach ($etyma as $csv_row) {
            $order += 10;
            $lex_etymon = new LexEtyma();
            $lex_etymon->order = $order;
            if ($file_is_nouns) {
                $lex_etymon->entry = $this->getEtymonEntryNoun($csv_row);
                $lex_etymon->gloss = $this->getEtymonGlossNoun($csv_row);
            } else if ($file_is_verbs) {
                $lex_etymon->entry = $this->getEtymonEntryVerb($csv_row);
                $lex_etymon->gloss = $this->getEtymonGlossVerb($csv_row);
            }
            if (!$lex_etymon->entry) {
                $lex_etymon->entry = 'No text for '.$csv_row['ID'];
            }
            if (!$lex_etymon->gloss) {
                $lex_etymon->gloss = 'No meaning/gloss for '.$csv_row['ID'];
            }

            $lex_etymon->created_by = "ps importer";
            $lex_etymon->updated_by = "ps importer";
            $lex_etymon->lexicon_id = $semitic_lexicon_id;
            $etymon_id = $csv_row['ID'];
            $extra_data = new \stdClass;
            foreach ($csv_row as $name=>$value) {
                if (in_array($name, ['ID', 'ETYMON ID', 'Etymon', 'root', 'meaning', 'language'])) {
                    continue;
                }
                if (trim($value)==="") {
                    continue;
                }
                $extra_data->{$name} = $value;
            }
            $lex_etymon->extra_data = $extra_data;
            $lex_etymon->save();
            $etyma_dbids[$etymon_id] = $lex_etymon->id;

            \Log::info($csv_row['ID'].' gloss: '.$lex_etymon->gloss.' | '.$lex_etymon->entry);
        }

        // store the reflexes
        foreach ($reflexes as $csv_row) {
            $csv_row['ETYMON ID'] = str_replace('?','',$csv_row['ETYMON ID']);
            $lang = LexLanguage::where('name', $csv_row['language'])->first();
            $lex_reflex = new LexReflex();
            $lex_reflex->language_id = $lang->id;
            $lex_reflex->lang_attribute = $iso_lang_codes[$csv_row['language']];
            $lex_reflex->class_attribute = 'Unicode';
            if ($file_is_nouns) {
                $entry = $this->getReflexEntryNoun($csv_row);
                $lex_reflex->gloss = $this->getReflexGlossNoun($csv_row);
            } else if ($file_is_verbs) {
                $entry = $this->getReflexEntryVerb($csv_row);
                $lex_reflex->gloss = $this->getReflexGlossVerb($csv_row);
            }
            if (!$entry) {
                $entry = 'No text for '.$csv_row['ID'];
            }
            if (!$lex_etymon->gloss) {
                $lex_etymon->gloss = 'No meaning/gloss for '.$csv_row['ID'];
            }
            $entries = array(['text'=>$entry]);
            $lex_reflex->entries = $entries;
            $lex_reflex->created_by = "ps importer";
            $lex_reflex->updated_by = "ps importer";
            $extra_data = new \stdClass;
            foreach ($csv_row as $name=>$value) {
                if (in_array($name, ['ID', 'ETYMON ID', 'root', 'meaning', 'language', 'Part of Speech'])) {
                    continue;
                }
                if (trim($value)==="") {
                    continue;
                }
                $extra_data->{$name} = $value;
            }
            $lex_reflex->extra_data = $extra_data;
            $lex_reflex->save();
            if ($csv_row['Part of Speech']) {
                $pos_text = strtolower($csv_row['Part of Speech']);
                $lexpos = LexPartOfSpeech::where('lexicon_id', $semitic_lexicon_id)
                    ->where('code', $pos_text)
                    ->first();
                if (!$lexpos) {
                    $lexpos = new LexPartOfSpeech();
                    $lexpos->lexicon_id = $semitic_lexicon_id;
                    $lexpos->code = $pos_text;
                    $lexpos->display = $pos_text;
                    $lexpos->created_by = "ps importer";
                    $lexpos->updated_by = "ps importer";
                    $lexpos->save();
                }
                $lrpos = new LexReflexPartOfSpeech();
                $lrpos->reflex_id = $lex_reflex->id;
                $lrpos->text = $pos_text;
                $lrpos->order = 10;
                $lrpos->created_by = "ps importer";
                $lrpos->updated_by = "ps importer";
                $lrpos->save();
            }
            if ($csv_row['ETYMON ID']) {
                $lex_etyma_reflex = new LexEtymaReflex();
                $lex_etyma_reflex->reflex_id = $lex_reflex->id;
                $lex_etyma_reflex->etyma_id = $etyma_dbids[$csv_row['ETYMON ID']];
                $lex_etyma_reflex->save();
            }

            \Log::info($csv_row['ID'].' gloss: '.$lex_reflex->gloss.' | '.json_encode($lex_reflex->entries));
        }

        });
    }

    // How do I get the etymon entry from the noun spreadsheet?
    protected function getEtymonEntryNoun($csv_row) {
        return $csv_row['Etymon'];
    }

    // How do I get the etymon entry from the verb spreadsheet?
    protected function getEtymonEntryVerb($csv_row) {
        return $csv_row['root'];
    }

    // How do I get the etymon gloss from the noun spreadsheet?
    protected function getEtymonGlossNoun($csv_row) {
        return $csv_row['meaning'];
    }

    // How do I get the etymon gloss from the verb spreadsheet?
    protected function getEtymonGlossVerb($csv_row) {
        return $csv_row['meaning'];
    }

    // How do I get the reflex entry from the noun spreadsheet?
    protected function getReflexEntryNoun($csv_row) {
        $translit = $csv_row['transliteration'];
        $script = $csv_row['script'];
        $sem_nor = $csv_row['Sem normalization'];
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
        $root_in_script = $csv_row['root in script'];
        $prefix_conj = $csv_row['prefix Conj 1'];
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

    // How do I get the reflex gloss from the noun spreadsheet?
    protected function getReflexGlossNoun($csv_row) {
        return $csv_row['meaning'];
    }

    // How do I get the reflex gloss from the verb spreadsheet?
    protected function getReflexGlossVerb($csv_row) {
        return $csv_row['meaning'];
    }
}
