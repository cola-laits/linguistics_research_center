<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \DB;
use \Normalizer;

class NormalizeUnicodeText extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lrc:normalize_unicode_text';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renormalize Unicode text to NFC';

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
     * @return mixed
     */
    public function handle()
    {
        \Log::info('updating eieol_gloss');
        $rows = DB::select('SELECT * FROM eieol_gloss');
        foreach ($rows as $row) {
            DB::update('update eieol_gloss'.
                ' SET surface_form=?,contextual_gloss=?,comments=?,underlying_form=?,author_comments=?,admin_comments=?'.
                ' WHERE id=?',[
                Normalizer::normalize($row->surface_form),
                Normalizer::normalize($row->contextual_gloss),
                Normalizer::normalize($row->comments),
                Normalizer::normalize($row->underlying_form),
                Normalizer::normalize($row->author_comments),
                Normalizer::normalize($row->admin_comments),
                $row->id
            ]);
        }

        \Log::info('updating eieol_glossed_text');
        $rows = DB::select('SELECT * FROM eieol_glossed_text');
        foreach ($rows as $row) {
            DB::update('update eieol_glossed_text'.
                ' SET glossed_text=?,author_comments=?,admin_comments=?'.
                ' WHERE id=?',[
                Normalizer::normalize($row->glossed_text),
                Normalizer::normalize($row->author_comments),
                Normalizer::normalize($row->admin_comments),
                $row->id
            ]);
        }

        \Log::info('updating eieol_grammar');
        $rows = DB::select('SELECT * FROM eieol_grammar');
        foreach ($rows as $row) {
            DB::update('update eieol_grammar'.
                ' SET title=?,grammar_text=?,author_comments=?,admin_comments=?'.
                ' WHERE id=?',[
                Normalizer::normalize($row->title),
                Normalizer::normalize($row->grammar_text),
                Normalizer::normalize($row->author_comments),
                Normalizer::normalize($row->admin_comments),
                $row->id
            ]);
        }

        \Log::info('updating eieol_head_word');
        $rows = DB::select('SELECT * FROM eieol_head_word');
        foreach ($rows as $row) {
            DB::update('update eieol_head_word'.
                ' SET word=?,definition=?'.
                ' WHERE id=?',[
                Normalizer::normalize($row->word),
                Normalizer::normalize($row->definition),
                $row->id
            ]);
        }

        \Log::info('updating eieol_lesson');
        $rows = DB::select('SELECT * FROM eieol_lesson');
        foreach ($rows as $row) {
            DB::update('update eieol_lesson'.
                ' SET title=?,intro_text=?,author_comments=?,admin_comments=?,lesson_translation=?,translation_author_comments=?,translation_admin_comments=?'.
                ' WHERE id=?',[
                Normalizer::normalize($row->title),
                Normalizer::normalize($row->intro_text),
                Normalizer::normalize($row->author_comments),
                Normalizer::normalize($row->admin_comments),
                Normalizer::normalize($row->lesson_translation),
                Normalizer::normalize($row->translation_author_comments),
                Normalizer::normalize($row->translation_admin_comments),
                $row->id
            ]);
        }

        \Log::info('updating lex_etyma');
        $rows = DB::select('SELECT * FROM lex_etyma');
        foreach ($rows as $row) {
            DB::update('update lex_etyma'.
                ' SET entry=?'.
                ' WHERE id=?',[
                Normalizer::normalize($row->entry),
                $row->id
            ]);
        }
    }
}
