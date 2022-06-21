<?php

namespace App\Http\Controllers;

use App\Models\LexEtyma;
use App\Models\LexLanguage;
use App\Models\LexLexicon;
use App\Models\LexReflex;
use App\Models\LexSemanticField;
use App\Models\Page;
use Illuminate\Http\Request;

class PublicLexiconController extends Controller
{
    public function index(Request $request, $lexicon_slug)
    {
        $lex = LexLexicon::where('slug', $lexicon_slug)->firstOrFail();
        return view('lexicon/lex_home', [
            'lexicon'=>$lex,
        ]);
    }

    public function etymon(Request $request, $lexicon_slug, $etymon_id)
    {
        $lex = LexLexicon::where('slug', $lexicon_slug)->firstOrFail();
        $etymon = LexEtyma::findOrFail($etymon_id);
        return view('lexicon/lex_etymon', [
            'lexicon'=>$lex,
            'etymon'=>$etymon,
        ]);
    }

    public function field(Request $request, $lexicon_slug, $field_id)
    {
        $lex = LexLexicon::where('slug', $lexicon_slug)->firstOrFail();
        $field = LexSemanticField::findOrFail($field_id);
        return view('lexicon/lex_field', [
            'lexicon'=>$lex,
            'field'=>$field,
        ]);
    }

    public function word_home(Request $request, $lexicon_slug, $word_id)
    {
        $lex = LexLexicon::where('slug', $lexicon_slug)->firstOrFail();
        $word = LexReflex::findOrFail($word_id);
        $language = $word->language;
        return view('lexicon/lex_word', [
            'lexicon'=>$lex,
            'language'=>$language,
            'word'=>$word,
        ]);
    }

    public function lang_home(Request $request, $lexicon_slug, $lang_id)
    {
        $lex = LexLexicon::where('slug', $lexicon_slug)->firstOrFail();
        $language = LexLanguage::findOrFail($lang_id);
        return view('lexicon/lex_language', [
            'lexicon'=>$lex,
            'language'=>$language,
        ]);
    }

    public function search(Request $request, $lexicon_slug)
    {
        $lex = LexLexicon::where('slug', $lexicon_slug)->firstOrFail();
        return view('lexicon/lex_search', [
            'lexicon'=>$lex,
        ]);
    }
}
