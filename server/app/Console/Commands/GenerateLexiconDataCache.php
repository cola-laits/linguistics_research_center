<?php

namespace App\Console\Commands;

use App\Models\LexLexicon;
use App\Models\LexReflex;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use function Laravel\Prompts\progress;

class GenerateLexiconDataCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-lexicon-data-cache {lexicon_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate the data cache for a Lexicon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->argument('lexicon_id')) {
            return $this->handleLexiconId($this->argument('lexicon_id'));
        } else {
            $lex_ids = LexLexicon::all()->pluck('id');
            foreach ($lex_ids as $lex_id) {
                $this->handleLexiconId($lex_id);
            }
        }
    }

    /**
     * Execute the console command.
     */
    protected function handleLexiconId($lex_id)
    {
        // The Lexicon '/data' page does a lot of complicated searching/sorting/filtering that leads to a
        // forest of joins.  Build a simple cache table for it to read from instead.

        $lexicon = LexLexicon::findOrFail($lex_id);
        $lex_language_ids = \DB::table('lex_language')
            ->join('lex_language_sub_family', 'lex_language.sub_family_id', '=', 'lex_language_sub_family.id')
            ->join('lex_language_family', 'lex_language_sub_family.family_id', '=', 'lex_language_family.id')
            ->where('lex_language_family.lexicon_id', $lex_id)
            ->pluck('lex_language.id');

        $lang_options = ['en'];
        if ($lexicon->viewer_lang_options) {
            $lang_options = str($lexicon->viewer_lang_options)->explode(',')->map(fn($l) => trim($l));
        }
        $num_reflexes = LexReflex::whereIn('language_id', $lex_language_ids)->count();
        $progress = progress(label: "Updating data cache for Lexicon '{$lexicon->name}'", steps: $num_reflexes);
        $progress->start();
        $column_descs = $lexicon->getDataColumns();

        \DB::table('lex_lexicon_data_cache')->where('lexicon_id', $lex_id)->delete();
        LexReflex::whereIn('language_id', $lex_language_ids)
            ->chunk(100, function(Collection $reflexes) use ($progress, $lang_options, $lex_id, $column_descs) {
                foreach ($reflexes as $reflex) {
                    foreach ($lang_options as $lang) {
                        $data = [];
                        app()->setLocale($lang); // set the locale so that automatic transation of results works
                        foreach ($column_descs as $column_desc) {
                            if ($column_desc->name == 'meaning') {
                                $data[$column_desc->name] = $reflex->gloss;
                            } else if ($column_desc->name == 'part_of_speech') {
                                $data[$column_desc->name] = $reflex->parts_of_speech->pluck('text')->join(', ');
                            } else if ($column_desc->name == 'semantic_tag') {
                                $data[$column_desc->name] = $reflex->etyma->flatMap(function ($etymon) {
                                    return $etymon->semantic_fields->pluck('text');
                                })->join(', ');
                            } else if ($column_desc->name == 'root') {
                                $data[$column_desc->name] = collect($reflex->entries)->pluck('text')->join(', ');
                            } else if ($column_desc->name == 'etymon') {
                                $data[$column_desc->name] = $reflex->etyma->pluck('entry')->join(', ');
                            } else if ($column_desc->name == 'language') {
                                $data[$column_desc->name] = $reflex->language->name;
                            } else {
                                $data[$column_desc->name] = $reflex->extra_data->where('key', $column_desc->name)->first()?->value ?? "";
                            }
                        }
                        \DB::table('lex_lexicon_data_cache')->insert([
                            'uuid' => \Str::uuid(),
                            'lexicon_id' => $lex_id,
                            'reflex_id' => $reflex->id,
                            'content_lang_code' => $lang,
                            'data' => json_encode((object)$data),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                    }
                    $progress->advance();
                }
            });
        $progress->finish();

        return 0;
    }
}
