<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Normalizer;

class UnicodeifyHtml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lrc:unicodify_html';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert HTML markup to its corresponding Unicode markup.';

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
        \Log::info('updating lex_etyma');
        $rows = DB::select('SELECT * FROM lex_etyma');
        foreach ($rows as $row) {
            DB::update('update lex_etyma'.
                ' SET entry=?'.
                ' WHERE id=?',[
                Normalizer::normalize($this->clean_markup($row->entry)),
                $row->id
            ]);
        }

        \Log::info('updating lesson');
        $rows = DB::select('SELECT * FROM eieol_lesson');
        foreach ($rows as $row) {
            DB::update('update eieol_lesson'.
                ' SET intro_text=?'.
                ' WHERE id=?',[
                Normalizer::normalize($this->clean_markup($row->intro_text)),
                $row->id
            ]);
        }

        \Log::info('updating head_word');
        $rows = DB::select('SELECT * FROM eieol_head_word');
        foreach ($rows as $row) {
            DB::update('update eieol_head_word'.
                ' SET word=?'.
                ' WHERE id=?',[
                Normalizer::normalize($this->clean_markup($row->word)),
                $row->id
            ]);
        }

        \Log::info('updating glossed_text');
        $rows = DB::select('SELECT * FROM eieol_glossed_text');
        foreach ($rows as $row) {
            DB::update('update eieol_glossed_text'.
                ' SET glossed_text=?'.
                ' WHERE id=?',[
                Normalizer::normalize($this->clean_markup($row->glossed_text)),
                $row->id
            ]);
        }

        \Log::info('updating gloss');
        $rows = DB::select('SELECT * FROM eieol_gloss');
        foreach ($rows as $row) {
            DB::update('update eieol_gloss'.
                ' SET surface_form=?'.
                ' WHERE id=?',[
                Normalizer::normalize($this->clean_markup($row->surface_form)),
                $row->id
            ]);
        }

        \Log::info('updating grammar');
        $rows = DB::select('SELECT * FROM eieol_grammar');
        foreach ($rows as $row) {
            DB::update('update eieol_grammar'.
                ' SET title=?'.
                ' ,grammar_text=?'.
                ' WHERE id=?',[
                Normalizer::normalize($this->clean_markup($row->title)),
                Normalizer::normalize($this->clean_markup($row->grammar_text)),
                $row->id
            ]);
        }

        \Log::info('updating language');
        $rows = DB::select('SELECT * FROM eieol_language');
        foreach ($rows as $row) {
            DB::update('update eieol_language'.
                ' SET substitutions=?'.
                ' ,custom_sort=?'.
                ' ,custom_keyboard_layout=?'.
                ' WHERE id=?',[
                Normalizer::normalize($this->clean_markup($row->substitutions)),
                Normalizer::normalize($this->clean_markup($row->custom_sort)),
                Normalizer::normalize($this->clean_markup($row->custom_keyboard_layout)),
                $row->id
            ]);
        }
    }

    protected function clean_markup($str) {
        $str = str_replace("ЪӀ","Ꙑ", $str);
        $str = str_replace("ъӏ","ꙑ", $str);
        $str = str_replace("ӀА","Ꙗ", $str);
        $str = str_replace("ӏа","ꙗ", $str);
        $str = str_replace("ЈА","Ꙗ", $str);
        $str = str_replace("ја","ꙗ", $str);

        $str = str_replace("Ӏ","І", $str);
        $str = str_replace("ӏ","і", $str);
        $str = str_replace("І","Ꙇ", $str);
        $str = str_replace("і","ꙇ", $str);
        return $str;
    }
}
