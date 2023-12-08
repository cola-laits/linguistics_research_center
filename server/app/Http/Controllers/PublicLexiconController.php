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
            'selected_sidebar'=>'headword',
        ]);
    }

    public function etymon(Request $request, $lexicon_slug, $etymon_id)
    {
        $lex = $this->getLexicon($lexicon_slug);
        $etymon = LexEtyma::with([
            'reflexes',
            'reflexes.language',
            'extra_data'
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
        $lex_language_ids = \DB::select(<<<EOQ
        SELECT lex_language.id FROM lex_language,lex_language_sub_family,lex_language_family
        WHERE lex_language_sub_family.id=lex_language.sub_family_id
        AND lex_language_family.id=lex_language_sub_family.family_id
        AND lex_language_family.lexicon_id=?
        EOQ, [$lex->id]);
        $lex_language_ids = array_column($lex_language_ids, 'id');

        $reflexes = LexReflex::with([
            'language',
            'etyma',
            'etyma.semantic_fields',
            'parts_of_speech',
        ])->whereIn('language_id', $lex_language_ids)->get();

        $column_order_string = null;
        // FIXME make this database-driven at some point
        if ($lexicon_slug === 'semitilex') {
            $column_order_string = <<<END
[
{"display_name": "Meaning", "name": "meaning"},
{"display_name": "Semantic Tag", "name": "semantic_tag"},
{"display_name": "Etymon", "name": "etymon"},
{"display_name": "pS Root", "name": "root"},
{"display_name": "Part of Speech", "name": "part_of_speech"},
{"display_name": "Language", "name": "language"},
{"display_name": "Verb Root", "name": "verb_root"},
{"display_name": "Verb Root Script", "name": "verb_root_script"},
{"display_name": "Script", "name": "script"},
{"display_name": "Transliteration", "name":"transliteration"},
{"display_name": "Sem Normalization", "name": "sem_normalization"},
{"display_name": "IPA Singular", "name": "ipa_singular"},
{"display_name": "Gender", "name":"gender"},
{"display_name": "Tag", "name": "tag"},
{"display_name": "Donor Language", "name": "donor_language"},
{"display_name": "Donor Word", "name": "donor_word"},
{"display_name": "Data Source", "name": "data_source"},
{"display_name": "Notes", "name": "notes"},
{"display_name": "f Markedness", "name": "f_markedness"},
{"display_name": "pS Pattern", "name": "ps_pattern"},
{"display_name": "Sem Normalization Pl", "name": "sem_normalization_pl"},
{"display_name": "IPA Plural", "name": "ipa_plural"},
{"display_name": "pS Plural Pattern", "name": "ps_plural_pattern"},
{"display_name": "pS Plural Suffix", "name": "ps_plural_suffix"},
{"display_name": "Deptotic", "name": "deptotic"},
{"display_name": "Prefix Conj 1", "name": "prefix_conj_1"},
{"display_name": "Prefix Conj 1 IPA", "name": "prefix_conj_1_ipa"},
{"display_name": "Prefix Conj 2", "name": "prefix_conj_2"},
{"display_name": "Prefix Conj 2 IPA", "name": "prefix_conj_2_ipa"},
{"display_name": "Suffix Conj", "name": "suffix_conj"},
{"display_name": "Suffix Conj IPA", "name": "suffix_conj_ipa"},
{"display_name": "Infinitive", "name": "infinitive"},
{"display_name": "Infinitive IPA", "name": "infinitive_ipa"},
{"display_name": "Participle", "name": "participle"},
{"display_name": "Participle IPA", "name": "participle_ipa"},
{"display_name": "PC Thematic Vowel", "name": "pc_thematic_vowel"},
{"display_name": "SC Thematic Vowel", "name": "sc_thematic_vowel"},
{"display_name": "Stem", "name": "stem"},
{"display_name": "Complement", "name": "complement"}
]
END;

        }

        if ($column_order_string === null) {
            $column_order_string = <<<END
[
{"display_name": "Meaning", "name": "meaning"},
{"display_name": "Semantic Tag", "name": "semantic_tag"},
{"display_name": "Etymon", "name": "etymon"}
]
END;
        }

        $column_descs = json_decode($column_order_string);

        $lookup_fn = function ($reflex, $column_name) use ($column_descs) {
            if ($column_name == 'meaning') { return $reflex->gloss; }
            if ($column_name == 'part_of_speech') { return $reflex->parts_of_speech->pluck('text')->join(', '); }
            if ($column_name == 'semantic_tag') {
                $tags = [];
                foreach ($reflex->etyma as $etymon) {
                    foreach ($etymon->semantic_fields as $field) {
                        $tags[] = $field->text;
                    }
                }
                return implode(', ', $tags);
            }
            if ($column_name == 'root') { return collect($reflex->entries)->pluck('text')->join(', '); }
            if ($column_name == 'etymon') { return $reflex->etyma->pluck('entry')->join(', '); }
            if ($column_name == 'language') { return $reflex->language->name; }
            return $reflex->extra_data[$column_name] ?? "";
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
