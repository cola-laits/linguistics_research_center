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
        if ($request->has('switchlang')) {
            Session::put('viewer_lang_code', $request->input('switchlang'));
            return redirect('/lexicon/'.$lexicon_slug);
        }
        $lex = $this->getLexicon($lexicon_slug);
        return view('lexicon/lex_home', [
            'lexicon'=>$lex,
            'selected_sidebar'=>'languages',
        ]);
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
        ])->whereIn('language_id', $lex_language_ids)->get();

        $column_descs = [];
        $column_descs []= (object) ['display_name'=>'Meaning', 'name'=>'meaning'];
        $column_descs []= (object) ['display_name'=>'Semantic Tag', 'name'=>'semantic_tag'];
        $column_descs []= (object) ['display_name'=>'Etymon', 'name'=>'etymon'];

        // FIXME make this database-driven at some point
        if ($lexicon_slug === 'semitilex') {
            $column_descs []= (object) ['display_name'=>'pS Root', 'name'=>'root'];
            $column_descs []= (object) ['display_name'=>'Part of Speech', 'name'=>'part_of_speech'];
            $column_descs []= (object) ['display_name'=>'Language', 'name'=>'language'];
            $column_descs []= (object) ['display_name'=>'Verb Root', 'name'=>'verb_root'];
            $column_descs []= (object) ['display_name'=>'Verb Root Script', 'name'=>'verb_root_script'];
            $column_descs []= (object) ['display_name'=>'Script', 'name'=>'script'];
            $column_descs []= (object) ['display_name'=>'Transliteration', 'name'=>'transliteration'];
            $column_descs []= (object) ['display_name'=>'Sem Normalization', 'name'=>'sem_normalization'];
            $column_descs []= (object) ['display_name'=>'IPA Singular', 'name'=>'ipa_singular'];
            $column_descs []= (object) ['display_name'=>'Gender', 'name'=>'gender'];
            $column_descs []= (object) ['display_name'=>'Tag', 'name'=>'tag'];
            $column_descs []= (object) ['display_name'=>'Donor Language', 'name'=>'donor_language'];
            $column_descs []= (object) ['display_name'=>'Donor Word', 'name'=>'donor_word'];
            $column_descs []= (object) ['display_name'=>'Data Source', 'name'=>'data_source'];
            $column_descs []= (object) ['display_name'=>'Notes', 'name'=>'notes'];
            $column_descs []= (object) ['display_name'=>'f Markedness', 'name'=>'f_markedness'];
            $column_descs []= (object) ['display_name'=>'pS Pattern', 'name'=>'ps_pattern'];
            $column_descs []= (object) ['display_name'=>'Sem Normalization Pl', 'name'=>'sem_normalization_pl'];
            $column_descs []= (object) ['display_name'=>'IPA Plural', 'name'=>'ipa_plural'];
            $column_descs []= (object) ['display_name'=>'pS Plural Pattern', 'name'=>'ps_plural_pattern'];
            $column_descs []= (object) ['display_name'=>'pS Plural Suffix', 'name'=>'ps_plural_suffix'];
            $column_descs []= (object) ['display_name'=>'Deptotic', 'name'=>'deptotic'];
            $column_descs []= (object) ['display_name'=>'Prefix Conj 1', 'name'=>'prefix_conj_1'];
            $column_descs []= (object) ['display_name'=>'Prefix Conj 1 IPA', 'name'=>'prefix_conj_1_ipa'];
            $column_descs []= (object) ['display_name'=>'Prefix Conj 2', 'name'=>'prefix_conj_2'];
            $column_descs []= (object) ['display_name'=>'Prefix Conj 2 IPA', 'name'=>'prefix_conj_2_ipa'];
            $column_descs []= (object) ['display_name'=>'Suffix Conj', 'name'=>'suffix_conj'];
            $column_descs []= (object) ['display_name'=>'Suffix Conj IPA', 'name'=>'suffix_conj_ipa'];
            $column_descs []= (object) ['display_name'=>'Infinitive', 'name'=>'infinitive'];
            $column_descs []= (object) ['display_name'=>'Infinitive IPA', 'name'=>'infinitive_ipa'];
            $column_descs []= (object) ['display_name'=>'Participle', 'name'=>'participle'];
            $column_descs []= (object) ['display_name'=>'Participle IPA', 'name'=>'participle_ipa'];
            $column_descs []= (object) ['display_name'=>'PC Thematic Vowel', 'name'=>'pc_thematic_vowel'];
            $column_descs []= (object) ['display_name'=>'SC Thematic Vowel', 'name'=>'sc_thematic_vowel'];
            $column_descs []= (object) ['display_name'=>'Stem', 'name'=>'stem'];
            $column_descs []= (object) ['display_name'=>'Complement', 'name'=>'complement'];
        }

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
                    return $reflex->extra_data[$column_name] ?? "";
            }
        };

        return view('lexicon/lex_data', [
            'lexicon'=>$lex,
            'reflexes'=>$reflexes,
            'columns'=>$column_descs,
            'display_value_lookup_fn'=>$lookup_fn,
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
