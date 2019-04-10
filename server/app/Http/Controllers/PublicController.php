<?php

namespace App\Http\Controllers;

use App\EieolLanguage;
use App\EieolLesson;
use App\EieolSeries;
use App\Helpers\AlphabetSorter;
use App\LexEtyma;
use App\LexLanguage;
use App\LexLanguageFamily;
use App\LexReflexEntry;
use App\LexSemanticCategory;
use App\LexSemanticField;
use App\Page;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index() {
        return view('index', [
            'content' => Page::whereSlug('index')->first()->content
        ]);
    }

    public function guide_ea() {
        return view('guide_ea', [
            'content' => Page::whereSlug('guides/eieol_author')->first()->content
        ]);
    }

    public function guide_eu() {
        return view('guide_eu', [
            'content' => Page::whereSlug('guides/eieol_user')->first()->content
        ]);
    }

    public function guide_lu() {
        return view('guide_lu', [
            'content' => Page::whereSlug('guides/lex_user')->first()->content
        ]);
    }


    //----------------------------------------EIEOL Functions--------------------------------------------

    public function eieol() {
        return view('eieol')->with([
            'content' => Page::whereSlug('eieol')->first()->content,
            'serieses' => EieolSeries::where('published', '=', True)->get()->sortBy('order')
        ]);
    }

    public function eieol_lesson_redirect(Request $request, $series_id) {
        $series = EieolSeries::find($series_id);

        if ($request->has('id')) {
            $lesson = EieolLesson::find($request->get('id'));
            return redirect('eieol/' . $series->slug . '/' . $lesson->order, 301);
        }

        return redirect('eieol/' . $series->slug, 301);
    }

    public function eieol_first_lesson($series_name) {
        $series = EieolSeries::findByIdOrSlug($series_name);

        $lesson = EieolLesson::where('series_id', $series->id)
            ->orderBy('order')
            ->firstOrFail();

        return $this->eieol_lesson($series_name, $lesson->order);
    }

    public function eieol_lesson($series_name, $lesson_order) {
        $series = EieolSeries::findByIdOrSlug($series_name);

        $data = [
            'series' => $series
        ];
        $data['printable'] = False;

        if ($series['use_old_gloss_ui']) {
            $data['clickable'] = False;
        } else {
            $data['clickable'] = True;
        }

        $data['lesson'] = EieolLesson::with('grammars', 'language')
            ->with('glossed_texts.glosses.language', 'glossed_texts.glosses.elements.head_word.language')
            ->where('series_id', '=', $series->id)
            ->where('order', '=', $lesson_order)
            ->firstOrFail();

        return view('eieol_lesson')->with($data);
    }

    public function eieol_printable($series_id) {
        $series = EieolSeries::findByIdOrSlug($series_id);

        $data = [
            'series' => $series
        ];

        $data['printable'] = True;
        $data['clickable'] = False;

        $html = view('printable_header_layout');

        $lessons = EieolLesson::with('grammars')
            ->with('glossed_texts.glosses.language', 'glossed_texts.glosses.elements.head_word.language')
            ->where('series_id', '=', $series->id)
            ->orderBy('order')
            ->get();

        $first = True;
        foreach ($lessons as $lesson) {
            if ($first) {
                $first = False;
            } else {
                $html .= '<div class="printable_footer"></div>';
            }

            $data['lesson'] = $lesson;
            $html .= view('eieol_lesson', $data);
        }

        $html .= view('printable_footer_layout');

        return $html;
    }

    public function eieol_toc($series_id) {
        $series = EieolSeries::findByIdOrSlug($series_id);

        return view('eieol_toc')->with([
            'series' => $series
        ]);
    }

    public function eieol_master_gloss($series_id, $language_id) {
        $series = EieolSeries::findByIdOrSlug($series_id);

        $data = [
            'series' => $series
        ];

        $data['language'] = EieolLanguage::find($language_id);
        $data['glosses'] = [];

        $lessons = EieolLesson::with('glossed_texts.glosses.elements.head_word.language')
            ->where('series_id', '=', $series->id)
            ->where('language_id', '=', $language_id)
            ->select(['id', 'title', 'order'])
            ->get()
            ->sortBy('order');

        //loop through all the lessons, glossed texts and glosses to group like glosses
        foreach ($lessons as $lesson) {

            foreach ($lesson->glossed_texts as $glossed_text) {

                foreach ($glossed_text->glosses as $gloss) {
                    //unique key is the surface form with all pos and analysis

                    $key = $gloss->surface_form . ' -- '
                         . $gloss->elements->map(function($element) {
                            return $element->part_of_speech . '; ' . $element->analysis;
                        })->implode(' + ');

                    //remove any tags like sup or sub
                    $key = strip_tags($key);

                    if (!array_key_exists($key, $data['glosses'])) {
                        $data['glosses'][$key] = $gloss->toArray();
                        $data['glosses'][$key]['surface_form'] = strip_tags($gloss->surface_form);
                        $data['glosses'][$key]['displayGlossForMasterGloss'] = $gloss->getDisplayGlossForMasterGloss();
                        $data['glosses'][$key]['glossed_text_gloss_ids'] = [];
                    }

                    $data['glosses'][$key]['glossed_text_gloss_ids'][$gloss->pivot->id] = $lesson;

                } //foreach gloss

            } //foreach glossed text

        } //foreach lesson

        foreach ($data['glosses'] as $key=>&$value) {
            $value['sortable_key'] = \Normalizer::normalize($value['surface_form'], \Normalizer::FORM_D);
        }
        unset($value);
        $sorter = new AlphabetSorter($data['language']->substitutions, $data['language']->custom_sort);
        uasort($data['glosses'], [$sorter, 'alphabet_sorter']);

        return view('eieol_master_gloss')->with($data);
    }

    public function eieol_base_form_dictionary($series_id, $language_id) {
        $series = EieolSeries::findByIdOrSlug($series_id);

        $data = [
            'series' => $series
        ];

        $data['language'] = EieolLanguage::findOrFail($language_id);
        $data['head_words'] = array();

        $lessons = EieolLesson::with('glossed_texts.glosses.elements.head_word.language', 'glossed_texts.glosses.elements.head_word.etyma')
            ->where('series_id', '=', $series->id)
            ->where('language_id', '=', $language_id)
            ->select(array('id', 'title', 'order'))
            ->get()
            ->sortBy('order');

        //loop through all the lessons, glossed texts and glosses to group like head words

        foreach ($lessons as $lesson) {

            foreach ($lesson->glossed_texts as $glossed_text) {

                foreach ($glossed_text->glosses as $gloss) {

                    foreach ($gloss->elements as $element) {
                        //unique key is head word plus definition

                        $key = $element->head_word->word . ' -- ' . $element->head_word->definition;
                        //remove first character, because it's a '<'
                        $key = mb_substr($key, 1, Null, 'UTF-8');
                        //remove any tags like sup or sub
                        $key = strip_tags($key);


                        if (!array_key_exists($key, $data['head_words'])) {

                            $data['head_words'][$key] = $element->head_word->toArray();

                            $data['head_words'][$key]['display'] = $element->head_word->getDisplayHeadWord();

                            $data['head_words'][$key]['glossed_text_gloss_ids'] = array();

                            $data['head_words'][$key]['glossed_text_gloss_ids'][$gloss->pivot->id] = $lesson;

                            //build sort key
                            //remove first character, because it's a '<
                            $sort_key = mb_substr($element->head_word->word, 1, Null, 'UTF-8');
                            //remove any tags like sup or sub
                            $sort_key = strip_tags($sort_key);
                            $data['head_words'][$key]['sortable_key'] = \Normalizer::normalize($sort_key, \Normalizer::FORM_D);
                        } else {

                            $data['head_words'][$key]['glossed_text_gloss_ids'][$gloss->pivot->id] = $lesson;

                        }

                    }

                }

            }

        }

        $sorter = new AlphabetSorter($data['language']->substitutions, $data['language']->custom_sort);

        uasort($data['head_words'], [$sorter, 'alphabet_sorter']);

        return view('eieol_base_form_dictionary')->with($data);
    }

    public function eieol_english_meaning_index($series_id, $language_id) {
        $series = EieolSeries::findByIdOrSlug($series_id);

        $data = [
            'series' => $series
        ];

        $data['language'] = EieolLanguage::find($language_id);

        $lessons = EieolLesson::with('glossed_texts.glosses.elements.head_word.language', 'glossed_texts.glosses.elements.head_word.keywords')
            ->where('series_id', '=', $series->id)
            ->where('language_id', '=', $language_id)
            ->select(array('id', 'title', 'order'))
            ->get()
            ->sortBy('order');

        $data['keywords'] = array();

        //loop through all the lessons, glossed texts and glosses to group like keywords

        foreach ($lessons as $lesson) {

            foreach ($lesson->glossed_texts as $glossed_text) {

                foreach ($glossed_text->glosses as $gloss) {

                    foreach ($gloss->elements as $element) {

                        foreach ($element->head_word->keywords as $keyword) {

                            $key = $keyword->keyword . ' -- ' . $element->head_word->word . ' -- ' . $element->head_word->definition;

                            if (!array_key_exists($key, $data['keywords'])) {

                                $data['keywords'][$key] = $keyword->toArray();

                                $data['keywords'][$key]['head_word'] = $element->head_word->getDisplayHeadWord();

                                $data['keywords'][$key]['glossed_text_gloss_ids'] = array();

                                $data['keywords'][$key]['glossed_text_gloss_ids'][$gloss->pivot->id] = $lesson;

                            } else {

                                $data['keywords'][$key]['glossed_text_gloss_ids'][$gloss->pivot->id] = $lesson;

                            }

                        }

                    }

                }

            }

        }

        ksort($data['keywords']);

        return view('eieol_english_meaning_index')->with($data);
    }

    public function eieol_text_list() {
        $data = [
            'text_list'=>[]
        ];

        $serieses = EieolSeries::where('published', '=', True)->get()->sortBy('order');
        foreach ($serieses as $series) {
            $text = [
                'id' => $series['id']
            ];

            $languages = $series->lesson_languages;
            if (count($languages) > 1) {
                foreach ($languages as $language) {
                    $text['title'] = $series['title'] . ' (' . $language['language'] . ')';
                    $text['language_id'] = $language['id'];
                    $data['text_list'][] = $text;
                }
            } else {
                $text['title'] = $series['title'];
                $text['language_id'] = 0;
                $data['text_list'][] = $text;
            }
        }
        return view('eieol_text_list')->with($data);
    }

    public function eieol_text_toc(Request $request, $series_id) {
        $data = [
            'series' => EieolSeries::findByIdOrSlug($series_id)
        ];
        if ($request->has('language_id')) {
            $language = EieolLanguage::find($request->get('language_id'));
            $data['language_id'] = $request->get('language_id');
            $data['series']['title'] .= ' (' . $language['language'] . ')';
            $data['lessons'] = EieolLesson::with('grammars', 'language')->where('series_id', '=', $series_id)->where('language_id', '=', $request->get('language_id'))->get()->sortBy('order');
        } else {
            $data['language_id'] = 0;
            $data['lessons'] = EieolLesson::with('grammars', 'language')->where('series_id', '=', $series_id)->get()->sortBy('order');
        }
        return view('eieol_text_toc')->with($data);
    }

    public function eieol_text(Request $request, $series_id) {
        $data = [
            'series' => EieolSeries::findByIdOrSlug($series_id)
        ];
        if ($request->get('language_id') != 0) {
            $language = EieolLanguage::find($request->get('language_id'));
            $data['series']['title'] .= ' (' . $language['language'] . ')';
        }
        $data['lesson'] = EieolLesson::with('language')
            ->with('glossed_texts.glosses.language', 'glossed_texts.glosses.elements.head_word.language')
            ->where('id', '=', $request->get('id'))
            ->firstOrFail();

        return view('eieol_text')->with($data);
    }


    //--------------------------------------------Lexicon Functions-----------------------------------------------


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
        $category = LexSemanticCategory::whereAbbr($cat_abbr)->firstOrFail();
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
            ->where("abbr", $field_abbr)->firstOrFail();
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
