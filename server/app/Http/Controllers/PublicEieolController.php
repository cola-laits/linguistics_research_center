<?php


namespace App\Http\Controllers;


use App\EieolLanguage;
use App\EieolLesson;
use App\EieolSeries;
use App\Helpers\AlphabetSorter;
use App\Page;
use Illuminate\Http\Request;

class PublicEieolController
{
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

                    $data['glosses'][$key]['glossed_text_gloss_ids'][$gloss->id] = $lesson;

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

                            $data['head_words'][$key]['glossed_text_gloss_ids'][$gloss->id] = $lesson;

                            //build sort key
                            //remove first character, because it's a '<
                            $sort_key = mb_substr($element->head_word->word, 1, Null, 'UTF-8');
                            //remove any tags like sup or sub
                            $sort_key = strip_tags($sort_key);
                            $data['head_words'][$key]['sortable_key'] = \Normalizer::normalize($sort_key, \Normalizer::FORM_D);
                        } else {

                            $data['head_words'][$key]['glossed_text_gloss_ids'][$gloss->id] = $lesson;

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

        $lessons = EieolLesson::with('glossed_texts.glosses.elements.head_word.language')
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

                        if (!$element->head_word->keywords) {
                            continue;
                        }

                        foreach (explode(',',$element->head_word->keywords) as $keyword) {
                            $key = $keyword . ' -- ' . $element->head_word->word . ' -- ' . $element->head_word->definition;

                            if (!array_key_exists($key, $data['keywords'])) {
                                $data['keywords'][$key] = [
                                    'keyword'=>$keyword,
                                    'head_word'=>$element->head_word->getDisplayHeadWord(),
                                    'glossed_text_gloss_ids'=>[]
                                ];
                            }

                            $data['keywords'][$key]['glossed_text_gloss_ids'][$gloss->id] = $lesson;
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
}
