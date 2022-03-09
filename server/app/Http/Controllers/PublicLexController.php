<?php


namespace App\Http\Controllers;


use App\Models\LexEtyma;
use App\Models\LexLanguage;
use App\Models\LexLanguageFamily;
use App\Models\LexReflex;
use App\Models\LexSemanticCategory;
use App\Models\LexSemanticField;
use DB;

use const false;

class PublicLexController extends Controller
{
    private static function split_entries($entry)
    {
        //entries might have some characters in ().  This means the entry is actually 2 entries, eg: Farv(e) would be Farv and Farve.
        //it is possible for an entry to have multiple parens, in which case we call this routine recursively.
        $open = mb_strpos($entry, '(', 0, 'UTF-8');
        $close = mb_strpos($entry, ')', 0, 'UTF-8');
        $first = mb_substr($entry, 0, $open, 'UTF-8');

        $len = $close - $open;
        $middle = mb_substr($entry, $open + 1, $len - 1, 'UTF-8');

        $len = mb_strlen($entry, 'UTF-8') - $close;
        $last = mb_substr($entry, $close + 1, $len, 'UTF-8');

        $short = $first . $last;
        $long = $first . $middle . $last;

        $keys = array();

        if (mb_strpos($short, '(', 0, 'UTF-8') === false) {
            $keys[] = $short;
        } else {
            //print_r(split_entries($short));
            $keys = array_merge($keys, self::split_entries($short));
        }

        if (mb_strpos($long, '(', 0, 'UTF-8') === false) {
            $keys[] = $long;
        } else {
            $keys = array_merge($keys, self::split_entries($long));
        }

        return $keys;
    }

    private static function hashKey($key, $alpha_weights)
    {
        //convert the key reflex to a series of numbers based on the weighted alphabet array for easy sorting.

        //break string into an array
        $key_array = preg_split('//u', $key, -1, PREG_SPLIT_NO_EMPTY);

        //build a hash of entry using weights.  So ab would become something like 00010002
        $key_parts = array_map(function ($key_char) use ($alpha_weights) {
            //these characters will not be used when sorting the keys of the array
            $the_unwanted = ["-", "*", "'"];
            if (in_array($key_char, $the_unwanted)) { //remove any unwanted characters
                return '';
            }
            if (array_key_exists($key_char, $alpha_weights)) {
                return str_pad($alpha_weights[$key_char], 4, '0', STR_PAD_LEFT);
            }

            return '0000'; //unknown characters become 0000 so they show up first
        }, $key_array);

        //Tack the original entry on to the end.  This way the keys remain unique even if it had unwanted chars, but the ending isn't really used for sorting
        return implode('', $key_parts) . $key;
    }

    public function lex_pokorny() {
        $etymas = LexEtyma::with('cross_references')->withCount('reflexes')->get()->sortBy('order');
        return view('lex_pokorny')->with([
            'etymas' => $etymas
        ]);
    }

    public function lex_reflex($pokorny_number) {
        $etyma = LexEtyma::with(
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
        $language_families = LexLanguageFamily::with('language_sub_families.languages.reflex_count')->get()->sortBy('order');

        return view('lex_language')->with([
            'language_families' => $language_families
        ]);
    }

    public function lex_lang_reflexes($language_abbr) {
        $language = LexLanguage::where("abbr", $language_abbr)->firstOrFail();
        $display_reflexes = [];

        $reflexes = LexReflex::whereLanguageId($language->id)->with('etymas')->get();

        //building the list of reflexes is complicated.
        $alpha_weights = [];
        $alphabet = explode(',',$language->custom_sort);
        foreach($alphabet as $ctr=>$alpha) {
            foreach (mb_str_split($alpha, 1, 'UTF-8') as $char) {
                $alpha_weights[$char] = $ctr+1;
            }
        }

        foreach ($reflexes as $reflex) {
            if (count($reflex->etymas) === 0) {
                continue;
            }
            foreach ($reflex->entries as $entry) {
                //special processing based on whether or not the entry has a ( in it
                $lacks_separator = mb_strpos($entry['text'], '(', 0, 'UTF-8') === false;
                $keys = $lacks_separator ? [$entry['text']] : self::split_entries($entry['text']);
                foreach ($keys as $key) {
                    $etymas = $reflex->etymas->map(function ($etyma) {
                        return [
                            'entry' => $etyma->entry,
                            'gloss' => $etyma->gloss,
                            'id' => $etyma->old_id
                        ];
                    })->sortBy('id')->toArray();

                    $new_key = self::hashKey($key, $alpha_weights);
                    $display_reflexes[$new_key] = [
                        'id' => $reflex->id,
                        'reflex' => $key,
                        'class_attribute' => $reflex->class_attribute,
                        'lang_attribute' => $reflex->lang_attribute,
                        'etymas' => $etymas
                    ];
                }
            }
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
