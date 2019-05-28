<?php


namespace App\Http\Controllers;


use App\LexEtyma;
use App\LexLanguage;
use App\LexLanguageFamily;
use App\LexReflexEntry;
use App\LexSemanticCategory;
use App\LexSemanticField;
use App\Page;

class PublicLexController
{
    public function lex() {
        $page = Page::whereSlug('lex')->first();
        $data = [
            'content' => $page->content
        ];

        return view('lex', $data);
    }

    public function lex_pokorny() {
        $etymas = LexEtyma::with('cross_references')->withCount('reflexes')->get()->sortBy('order');
        $data = [
            'etymas' => $etymas
        ];
        return view('lex_pokorny')->with($data);
    }

    public function lex_reflex($pokorny_number) {
        $etyma = LexEtyma::with('reflexes.entries',
            'reflexes.language.language_sub_family.language_family',
            'reflexes.sources',
            'reflexes.parts_of_speech',
            'semantic_fields.semantic_category')->where('old_id', '=', $pokorny_number)->firstOrFail();
        $data = [
            'etyma' => $etyma
        ];

        return view('lex_reflex')->with($data);
    }

    public function lex_language() {
        $language_families = LexLanguageFamily::with('language_sub_families.languages.reflex_count')->get()->sortBy('order');
        $data = [
            'language_families' => $language_families
        ];
        return view('lex_language')->with($data);
    }

    public function lex_lang_reflexes($language_abbr) {
        $language = LexLanguage::where("abbr", $language_abbr)->firstOrFail();
        $data = [
            'language' => $language,
            'display_reflexes' => []
        ];
        $language_id = $language->id;

        //get all the reflexes.  The Eloquent ORM is too slow, so we have to write our own SQL
        $temp_reflexes = \DB::select(\DB::raw("SELECT lex_reflex.id, lex_reflex.class_attribute, lex_reflex.lang_attribute, 
													 lex_reflex_entry.entry, 
													 lex_etyma.entry as etyma_entry, lex_etyma.old_id as etyma_id, lex_etyma.gloss 
				FROM lex_reflex, lex_reflex_entry, lex_etyma_reflex, lex_etyma 
				WHERE language_id = '$language_id'
				AND lex_reflex_entry.reflex_id = lex_reflex.id 
				AND lex_etyma_reflex.reflex_id = lex_reflex.id 
				AND lex_etyma.id = lex_etyma_reflex.etyma_id"));

        //building the list of reflexes is complicated.
        $alpha_weights = $language->getWeights();

        foreach ($temp_reflexes as $reflex) {

            //now build array of reflexes, combining where needed.
            foreach (LexReflexEntry::keys($reflex->entry) as $key) {
                $new_key = LexReflexEntry::hashKey($key, $alpha_weights);

                //if 2 reflexes are the same, group them
                if (array_key_exists($new_key, $data['display_reflexes'])) {
                    $temp_etyma = [
                        'entry' => $reflex->etyma_entry,
                        'gloss' => $reflex->gloss,
                        'id' => $reflex->etyma_id
                    ];
                    $data['display_reflexes'][$new_key]['etymas'][] = $temp_etyma;
                    ksort($data['display_reflexes'][$new_key]['etymas']); //sort the etymas
                } else {
                    $new_reflex = [
                        'id' => $reflex->id,
                        'reflex' => $key,
                        'class_attribute' => $reflex->class_attribute,
                        'lang_attribute' => $reflex->lang_attribute,
                        'etymas' => []
                    ];
                    $temp_etyma = [
                        'entry' => $reflex->etyma_entry,
                        'gloss' => $reflex->gloss,
                        'id' => $reflex->etyma_id
                    ];
                    $new_reflex['etymas'][] = $temp_etyma;

                    $data['display_reflexes'][$new_key] = $new_reflex;
                }
            } //foreach key
        } //foreach reflex

        //we have to use a string sort or it will think these are ints and shortest entries will come first
        ksort($data['display_reflexes'], $sort_flags = SORT_STRING);

        return view('lex_lang_reflexes')->with($data);
    }

    public function lex_semantic() {
        $data = [
            'cats' => LexSemanticCategory::get()->sortBy('number'),
            'alpha_cats' => LexSemanticCategory::get()->sortBy('text')
        ];
        return view('lex_semantic')->with($data);
    }

    public function lex_semantic_category($cat_abbr) {
        $category = LexSemanticCategory::whereAbbr($cat_abbr)->first();
        $alpha_cats = LexSemanticCategory::get()->sortBy('text');
        $fields = LexSemanticField::withCount('etymas')
            ->where('semantic_category_id', '=', $category->id)
            ->get()
            ->sortBy('number');
        $data = [
            'cat'=>$category,
            'alpha_cats'=>$alpha_cats,
            'fields'=>$fields
        ];

        return view('lex_semantic_category')->with($data);
    }

    public function lex_semantic_field($field_abbr) {
        $field = LexSemanticField::with('etymas', 'semantic_category')
            ->where("abbr", $field_abbr)->first();
        $alpha_cats = LexSemanticCategory::get()->sortBy('text');

        $data = [
            'field'=>$field,
            'alpha_cats' => $alpha_cats
        ];
        return view('lex_semantic_field')->with($data);
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
