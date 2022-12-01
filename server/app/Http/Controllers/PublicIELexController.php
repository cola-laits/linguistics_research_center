<?php

/** @noinspection PhpMissingReturnTypeInspection */
/** @noinspection PhpUnused */

namespace App\Http\Controllers;

use App\Models\LexEtyma;
use App\Models\LexLanguage;
use App\Models\LexLanguageFamily;
use App\Models\LexReflex;
use App\Models\LexSemanticCategory;
use App\Models\LexSemanticField;

class PublicIELexController extends Controller
{
    const IELEX_ID = 1;

    public function lex_pokorny() {
        $etymas = LexEtyma::where('lexicon_id',self::IELEX_ID)
            ->with('cross_references')
            ->withCount('reflexes')
            ->orderBy('order')
            ->get();
        return view('lex_pokorny')->with([
            'etymas' => $etymas
        ]);
    }

    public function lex_reflex($pokorny_number) {
        $etyma = LexEtyma::where('lexicon_id',self::IELEX_ID)->with(
            'reflexes.language.language_sub_family.language_family',
            'reflexes.sources',
            'reflexes.parts_of_speech',
            'semantic_fields.semantic_category')
            ->where('old_id', '=', $pokorny_number)
            ->firstOrFail();

        return view('lex_reflex')->with([
            'etyma' => $etyma
        ]);
    }

    public function lex_language() {
        $language_families = LexLanguageFamily::where('lexicon_id',self::IELEX_ID)
            ->with('language_sub_families.languages.reflex_count')
            ->orderBy('order')
            ->get();

        return view('lex_language')->with([
            'language_families' => $language_families
        ]);
    }

    public function lex_lang_reflexes($language_abbr) {
        $language = LexLanguage::where("abbr", $language_abbr)->firstOrFail();

        $alpha_weights = $language->getCharSortWeights();

        $reflexes = LexReflex::whereLanguageId($language->id)
            ->with('etymas:id,old_id,entry,gloss')
            ->select(['id', 'language_id', 'lang_attribute', 'class_attribute', 'gloss', 'entries'])
            ->get()
            ->filter(fn ($reflex) => count($reflex->etymas) > 0)
        ;

        $display_reflexes = [];
        foreach ($reflexes as $reflex) {
            $display_reflexes = array_merge($display_reflexes, $reflex->get_collatable_entries($alpha_weights));
        } //foreach reflex

        //we have to use a string sort or it will think these are ints and shortest entries will come first
        ksort($display_reflexes, $sort_flags = SORT_STRING);

        return view('lex_lang_reflexes')->with([
            'language' => $language,
            'display_reflexes' => $display_reflexes,
        ]);
    }

    public function lex_semantic() {
        return view('lex_semantic')->with([
            'cats' => LexSemanticCategory::where('lexicon_id',self::IELEX_ID)
                ->orderBy('number')
                ->get(),
            'alpha_cats' => LexSemanticCategory::where('lexicon_id',self::IELEX_ID)
                ->orderBy('text')
                ->get()
        ]);
    }

    public function lex_semantic_category($cat_abbr) {
        $category = LexSemanticCategory::where('lexicon_id',self::IELEX_ID)
            ->whereAbbr($cat_abbr)->firstOrFail();
        $alpha_cats = LexSemanticCategory::where('lexicon_id',self::IELEX_ID)
            ->orderBy('text')
            ->get();
        $fields = LexSemanticField::withCount('etymas')
            ->where('semantic_category_id', '=', $category->id)
            ->orderBy('number')
            ->get();

        return view('lex_semantic_category')->with([
            'cat'=>$category,
            'alpha_cats'=>$alpha_cats,
            'fields'=>$fields
        ]);
    }

    public function lex_semantic_field($field_abbr) {
        $field = LexSemanticField::with('etymas', 'semantic_category')
            ->where("abbr", $field_abbr)->firstOrFail();
        $alpha_cats = LexSemanticCategory::where('lexicon_id',self::IELEX_ID)
            ->orderBy('text')
            ->get();

        return view('lex_semantic_field')->with([
            'field'=>$field,
            'alpha_cats' => $alpha_cats
        ]);
    }

    // ** redirections for old lex routes
    public function lex_lang_reflexes_redirect($language_id) {
        $language = LexLanguage::find($language_id);
        return redirect('lex/languages/' . $language->abbr, 301);
    }

    public function lex_semantic_field_redirect($field_id) {
        $field = LexSemanticField::find($field_id);
        return redirect('lex/semantic/field/' . $field->abbr, 301);
    }

    public function lex_reflex_redirect($etyma_id) {
        $etyma = LexEtyma::find($etyma_id);
        return redirect('lex/master/' . $etyma->old_id, 301);//pokorny number is stored in db column 'old_id'
    }

}
