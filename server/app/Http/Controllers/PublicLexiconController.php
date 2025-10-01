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
use Illuminate\Database\Query\Builder;
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

    public function data($lexicon_slug)
    {
        $lex = LexLexicon::where('slug', $lexicon_slug)->firstOrFail();

        return view('lexicon/lex_data', [
            'lexicon'=>$lex,
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

    public function ajaxData($lex_slug)
    {
        $start = request()->integer('start', 0);
        $length = request()->integer('length', 10);
        if ($length>100) {
            $length = 100;
        }

        $lex = LexLexicon::where('slug', $lex_slug)->firstOrFail();
        $viewer_locale = Session::get('viewer_lang_code', 'en');

        $reflex_count = \DB::table('lex_lexicon_data_cache')
            ->where('lexicon_id', $lex->id)
            ->where('content_lang_code', $viewer_locale)
            ->count();

        $filtered_reflexes = \DB::table('lex_lexicon_data_cache')
            ->where('lexicon_id', $lex->id)
            ->where('content_lang_code', $viewer_locale);

        $columns = request()->input('columns');
        foreach ($columns as $column) {
            if ($column['search']['value']) {
                if ($column['search']['regex']) {
                    $filtered_reflexes = $filtered_reflexes->where('data->' . $column['name'], 'REGEXP', $column['search']['value']);
                } else {
                    $filtered_reflexes = $filtered_reflexes->where('data->' . $column['name'], 'LIKE', '%' . $column['search']['value'] . '%');
                }
            }
        }

        $search = request()->input('search');
        if ($search['value']) {
            // if regex... FIXME
            $filtered_reflexes = $filtered_reflexes->where(function (Builder $q) use ($columns, $search) {
                foreach ($columns as $column) {
                    if ($search['regex']) {
                        $q = $q->orWhere('data->' . $column['name'], 'REGEXP', $search['value']);
                    } else {
                        $q = $q->orWhere('data->' . $column['name'], 'LIKE', '%' . $search['value'] . '%');
                    }
                }
            });
        }

        $order = request()->input('order');
        if ($order) {
            $order_by_key = $order[0]['name'];
            $order_by_dir = $order[0]['dir'];
            $filtered_reflexes->orderBy('data->'.$order_by_key, $order_by_dir);
        }

        $filtered_reflexes_count = $filtered_reflexes->count();
        $data = $filtered_reflexes
            ->skip($start)
            ->limit($length)
            ->get()
            ->map(function($r) {
                $d = json_decode($r->data);
                $d->id = $r->reflex_id;
                return $d;
            });

        return (object)[
            'draw' => (int)request()->input('draw'),
            'recordsTotal' => $reflex_count,
            'recordsFiltered' => $filtered_reflexes_count,
            'data' => $data,
        ];
    }
}
