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
        $lex = $this->getLexicon($lexicon_slug);
        return view('lexicon/lex_home', [
            'lexicon'=>$lex,
            'selected_sidebar'=>'headword',
        ]);
    }

    public function etymon(Request $request, $lexicon_slug, $etymon_id)
    {
        $lex = $this->getLexicon($lexicon_slug);
        $etymon = LexEtyma::with([
            'reflexes',
            'reflexes.language'
        ])->findOrFail($etymon_id);
        return view('lexicon/lex_etymon', [
            'lexicon'=>$lex,
            'etymon'=>$etymon,
            'selected_sidebar'=>'headword',
            'selected_sidebar_id'=>$etymon->id,
        ]);
    }

    public function field(Request $request, $lexicon_slug, $field_id)
    {
        $lex = $this->getLexicon($lexicon_slug);
        $field = LexSemanticField::with([
            'etyma'
        ])->findOrFail($field_id);
        return view('lexicon/lex_field', [
            'lexicon'=>$lex,
            'field'=>$field,
            'selected_sidebar'=>'category',
            'selected_sidebar_id'=>$field->id,
        ]);
    }

    public function word_home(Request $request, $lexicon_slug, $word_id)
    {
        $lex = $this->getLexicon($lexicon_slug);
        $word = LexReflex::with([
            'etyma',
            'etyma.reflexes',
            'etyma.reflexes.language',
        ])->findOrFail($word_id);
        $language = $word->language;
        return view('lexicon/lex_word', [
            'lexicon'=>$lex,
            'language'=>$language,
            'word'=>$word,
            'selected_sidebar'=>'headword',
            'selected_sidebar_id'=>$word->id,
        ]);
    }

    public function lang_home(Request $request, $lexicon_slug, $lang_id)
    {
        $lex = $this->getLexicon($lexicon_slug);
        $language = LexLanguage::findOrFail($lang_id);
        return view('lexicon/lex_language', [
            'lexicon'=>$lex,
            'language'=>$language,
            'selected_sidebar'=>'headword',
        ]);
    }

    public function search(Request $request, $lexicon_slug)
    {
        $lex = $this->getLexicon($lexicon_slug);
        return view('lexicon/lex_search', [
            'lexicon'=>$lex,
        ]);
    }

    public function page(Request $request, $lexicon_slug, $page_slug_fragment)
    {
        $lex = $this->getLexicon($lexicon_slug);
        $page_url = "lexicon/".$lexicon_slug.'/page/'.$page_slug_fragment;
        $page = Page::where('slug', $page_url)->firstOrFail();
        return view('lexicon/lex_page', [
            'lexicon'=>$lex,
            'page'=>$page,
            'selected_sidebar'=>'headword',
        ]);
    }

    protected function getLexicon($lexicon_slug)
    {
        return LexLexicon::where('slug', $lexicon_slug)
            ->with([
                'language_families',
                'language_families.language_sub_families',
                'language_families.language_sub_families.languages',
                'semantic_categories',
                'semantic_categories.semantic_fields',
            ])->firstOrFail();
    }
}
