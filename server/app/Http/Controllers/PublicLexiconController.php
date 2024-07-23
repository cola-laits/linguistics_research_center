<?php

/** @noinspection PhpUnused */
/** @noinspection PhpMissingReturnTypeInspection */

namespace App\Http\Controllers;

use App\Models\LexEtyma;
use App\Models\LexLanguage;
use App\Models\LexLexicon;
use App\Models\LexReflex;
use App\Models\LexSemanticField;
use App\Models\Page;
use Illuminate\Http\Request;
use Session;

class PublicLexiconController extends Controller
{
    public function index(Request $request, $lexicon_slug)
    {
        $lex = $this->getLexicon($lexicon_slug);
        return view('lexicon/lex_home', [
            'lexicon'=>$lex,
            'selected_sidebar'=>'language',
        ]);
    }

    public function switch_lang(Request $request, $lexicon_slug, $lang)
    {
        Session::put('viewer_lang_code', $lang);
        if ($request->get('return_to')) {
            return redirect($request->get('return_to'));
        }
        return redirect('/lexicon/'.$lexicon_slug);
    }

    public function protolanguage_home(Request $request, $lexicon_slug)
    {
        $lex = $this->getLexicon($lexicon_slug);
        return view('lexicon/lex_protolanguage_home', [
            'lexicon'=>$lex,
            'protolang'=>true,
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
            'etyma',
            'etyma.reflexes',
            'etyma.reflexes.language',
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

    public function data(Request $request, $lexicon_slug)
    {
        ini_set('memory_limit', '2048M');

        $lex = LexLexicon::where('slug', $lexicon_slug)->firstOrFail();
        $lex_language_ids = \DB::table('lex_language')
            ->join('lex_language_sub_family', 'lex_language.sub_family_id', '=', 'lex_language_sub_family.id')
            ->join('lex_language_family', 'lex_language_sub_family.family_id', '=', 'lex_language_family.id')
            ->where('lex_language_family.lexicon_id', $lex->id)
            ->pluck('lex_language.id');

        $reflexes = LexReflex::with([
            'language',
            'etyma',
            'etyma.semantic_fields',
            'parts_of_speech',
            'extra_data',

        ])->whereIn('language_id', $lex_language_ids)
            ->limit(10000)
            ->get();

        $column_descs = $lex->getDataColumns();

        $lookup_fn = function ($reflex, $column_name) {
            switch ($column_name) {
                case 'meaning':
                    return $reflex->gloss;
                case 'part_of_speech':
                    return $reflex->parts_of_speech->pluck('text')->join(', ');
                case 'semantic_tag':
                    return $reflex->etyma->flatMap(function ($etymon) {
                        return $etymon->semantic_fields->pluck('text');
                    })->join(', ');
                case 'root':
                    return collect($reflex->entries)->pluck('text')->join(', ');
                case 'etymon':
                    return $reflex->etyma->pluck('entry')->join(', ');
                case 'language':
                    return $reflex->language->name;
                default:
                    return $reflex->extra_data->where('key', $column_name)->first()?->value ?? "";
            }
        };

        $reflex_data = $reflexes->map(function ($reflex) use ($column_descs, $lookup_fn) {
            $data = collect($column_descs)->mapWithKeys(function ($column_desc) use ($reflex, $lookup_fn) {
                return [$column_desc->name => $lookup_fn($reflex, $column_desc->name)];
            });
            $data['id'] = $reflex->id;
            return $data;
        });

        return view('lexicon/lex_data', [
            'lexicon'=>$lex,
            'reflexes'=>$reflex_data,
            'columns'=>$column_descs,
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
