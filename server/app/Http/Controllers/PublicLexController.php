<?php


namespace App\Http\Controllers;


use App\LexEtyma;
use App\LexLanguage;
use App\LexLanguageFamily;
use App\LexReflexEntry;
use App\LexSemanticCategory;
use App\LexSemanticField;
use DB;

class PublicLexController
{
    public function lex_pokorny() {
        $etymas = LexEtyma::with('cross_references')->withCount('reflexes')->get()->sortBy('order');
        return view('lex_pokorny')->with([
            'etymas' => $etymas
        ]);
    }

    public function lex_reflex($pokorny_number) {
        $etyma = LexEtyma::with('reflexes.entries',
            'reflexes.language.language_sub_family.language_family',
            'reflexes.sources',
            'reflexes.parts_of_speech',
            'semantic_fields.semantic_category')->where('old_id', '=', $pokorny_number)->firstOrFail();

        return view('lex_reflex')->with([
            'etyma' => $etyma
        ]);
    }

    public function lex_language() {
        $language_families = LexLanguageFamily::with('language_sub_families.languages.reflex_count')->get()->sortBy('order');

        return view('lex_language')->with([
            'language_families' => $language_families
        ]);
    }

    public function lex_lang_reflexes($language_abbr) {
        $language = LexLanguage::where("abbr", $language_abbr)->firstOrFail();
        $display_reflexes = [];

        //get all the reflexes.  The Eloquent ORM is too slow, so we have to write our own SQL
        $temp_reflexes = DB::select('SELECT lex_reflex.id, lex_reflex.class_attribute, lex_reflex.lang_attribute, 
													 lex_reflex_entry.entry, 
													 lex_etyma.entry as etyma_entry, lex_etyma.old_id as etyma_id, lex_etyma.gloss 
				FROM lex_reflex, lex_reflex_entry, lex_etyma_reflex, lex_etyma 
				WHERE language_id = ?
				AND lex_reflex_entry.reflex_id = lex_reflex.id 
				AND lex_etyma_reflex.reflex_id = lex_reflex.id 
				AND lex_etyma.id = lex_etyma_reflex.etyma_id', [$language->id]);

        //building the list of reflexes is complicated.
        $alpha_weights = $language->getWeights();

        foreach ($temp_reflexes as $reflex) {

            //now build array of reflexes, combining where needed.
            foreach (LexReflexEntry::keys($reflex->entry) as $key) {
                $new_key = LexReflexEntry::hashKey($key, $alpha_weights);

                //if 2 reflexes are the same, group them
                if (!array_key_exists($new_key, $display_reflexes)) {
                    $new_reflex = [
                        'id' => $reflex->id,
                        'reflex' => $key,
                        'class_attribute' => $reflex->class_attribute,
                        'lang_attribute' => $reflex->lang_attribute,
                        'etymas' => []
                    ];
                    $display_reflexes[$new_key] = $new_reflex;
                }

                $temp_etyma = [
                    'entry' => $reflex->etyma_entry,
                    'gloss' => $reflex->gloss,
                    'id' => $reflex->etyma_id
                ];
                $display_reflexes[$new_key]['etymas'][] = $temp_etyma;
                ksort($display_reflexes[$new_key]['etymas']); //sort the etymas

            } //foreach key
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
            'cats' => LexSemanticCategory::get()->sortBy('number'),
            'alpha_cats' => LexSemanticCategory::get()->sortBy('text')
        ]);
    }

    public function lex_semantic_category($cat_abbr) {
        $category = LexSemanticCategory::whereAbbr($cat_abbr)->firstOrFail();
        $alpha_cats = LexSemanticCategory::get()->sortBy('text');
        $fields = LexSemanticField::withCount('etymas')
            ->where('semantic_category_id', '=', $category->id)
            ->get()
            ->sortBy('number');

        return view('lex_semantic_category')->with([
            'cat'=>$category,
            'alpha_cats'=>$alpha_cats,
            'fields'=>$fields
        ]);
    }

    public function lex_semantic_field($field_abbr) {
        $field = LexSemanticField::with('etymas', 'semantic_category')
            ->where("abbr", $field_abbr)->firstOrFail();
        $alpha_cats = LexSemanticCategory::get()->sortBy('text');

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
