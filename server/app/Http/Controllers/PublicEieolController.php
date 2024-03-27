<?php

/** @noinspection PhpMissingReturnTypeInspection */
/** @noinspection PhpUnused */

namespace App\Http\Controllers;

use App\Models\BlockPage;
use App\Models\EieolLanguage;
use App\Models\EieolLesson;
use App\Models\EieolSeries;
use App\Helpers\AlphabetSorter;
use App\Models\Page;
use Illuminate\Http\Request;

class PublicEieolController extends Controller
{
    public function eieol() {
        return view('eieol', [
            'content' => Page::whereSlug('eieol')->first()->content,
            'serieses' => EieolSeries::where('published', '=', True)
                ->orderBy('order')
                ->get()
        ]);
    }

    public function eieol_lesson_redirect(Request $request, $series_id) {
        $series = EieolSeries::findOrFail($series_id);

        if ($request->has('id')) {
            $lesson = EieolLesson::findOrFail($request->get('id'));
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

        $lesson = EieolLesson::with('grammars', 'language')
            ->with('glossed_texts.glosses.language', 'glossed_texts.glosses.elements.head_word.language')
            ->where('series_id', '=', $series->id)
            ->where('order', '=', $lesson_order)
            ->firstOrFail();

        $page_with_blocks = BlockPage::with('blocks.blockable')->findOrFail($lesson->block_page_id);
        dd($page_with_blocks->blocks[0]->blockable);

        return view('eieol_lesson', [
            'series' => $series,
            'printable' => False,
            'clickable' => True,
            'lesson' => $lesson
        ]);
    }

    public function eieol_printable($series_id) {
        $series = EieolSeries::findByIdOrSlug($series_id);

        $html = view('printable_header_layout');

        $lessons = EieolLesson::with('grammars')
            ->with('glossed_texts.glosses.language', 'glossed_texts.glosses.elements.head_word.language')
            ->where('series_id', '=', $series->id)
            ->orderBy('order')
            ->get();

        foreach ($lessons as $index => $lesson) {
            if ($index != 0) {
                $html .= '<div class="printable_footer"></div>';
            }

            $html .= view('eieol_lesson', [
                'series' => $series,
                'printable' => True,
                'clickable' => False,
                'lesson' => $lesson
            ]);
        }

        $html .= view('printable_footer_layout');

        return $html;
    }

    public function eieol_toc($series_id) {
        $series = EieolSeries::findByIdOrSlug($series_id);

        return view('eieol_toc', [
            'series' => $series
        ]);
    }

    public function eieol_master_gloss($series_id, $language_id) {
        $series = EieolSeries::findByIdOrSlug($series_id);
        $language = EieolLanguage::findOrFail($language_id);
        $glosses = [];

        $lessons = EieolLesson::with('glossed_texts.glosses.elements.head_word.language')
            ->where('series_id', '=', $series->id)
            ->where('language_id', '=', $language_id)
            ->select(['id', 'title', 'order'])
            ->orderBy('order')
            ->get();

        foreach ($lessons as $lesson) {
            foreach ($lesson->glossed_texts as $glossed_text) {
                foreach ($glossed_text->glosses as $gloss) {
                    //unique key is the surface form with all pos and analysis

                    $key = sha1($gloss->surface_form . ' -- '
                        . $gloss->elements->map(function($element) {
                            return $element->part_of_speech . '; ' . $element->analysis . ':' . $element->head_word_id;
                        })->implode(' + '));

                    if (!isset($glosses[$key])) {
                        $glosses[$key] = $gloss->toArray();
                        $glosses[$key]['surface_form'] = strip_tags($gloss->surface_form);
                        $glosses[$key]['gloss'] = $gloss;
                        $glosses[$key]['glossed_text_gloss_ids'] = [];
                    }

                    $glosses[$key]['glossed_text_gloss_ids'][$gloss->id] = $lesson;

                }
            }
        }

        array_walk($glosses, fn(&$value) =>
            $value['sortable_key'] = \Normalizer::normalize($value['surface_form'], \Normalizer::FORM_D)
        );
        $sorter = new AlphabetSorter($language->substitutions, $language->custom_sort);
        uasort($glosses, [$sorter, 'alphabet_sorter']);

        return view('eieol_master_gloss', [
            'series' => $series,
            'language' => $language,
            'glosses' => $glosses,
        ]);
    }

    public function eieol_base_form_dictionary($series_id, $language_id) {
        $series = EieolSeries::findByIdOrSlug($series_id);
        $language = EieolLanguage::findOrFail($language_id);
        $head_words = [];

        $lessons = EieolLesson::with('glossed_texts.glosses.elements.head_word.language', 'glossed_texts.glosses.elements.head_word.etyma')
            ->where('series_id', '=', $series->id)
            ->where('language_id', '=', $language_id)
            ->select(['id', 'title', 'order'])
            ->orderBy('order')
            ->get();

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

                        if (!isset($head_words[$key])) {
                            //build sort key
                            //remove first character, because it's a '<
                            $sort_key = mb_substr($element->head_word->word, 1, Null, 'UTF-8');
                            //remove any tags like sup or sub
                            $sort_key = strip_tags($sort_key);
                            $sort_key = \Normalizer::normalize($sort_key, \Normalizer::FORM_D);

                            $head_words[$key] = [
                                'model' => $element->head_word,
                                'glossed_text_gloss_ids' => [],
                                'sortable_key' => $sort_key,
                            ];
                        }

                        $head_words[$key]['glossed_text_gloss_ids'][$gloss->id] = $lesson;

                    }
                }
            }
        }

        $sorter = new AlphabetSorter($language->substitutions, $language->custom_sort);

        uasort($head_words, [$sorter, 'alphabet_sorter']);

        return view('eieol_base_form_dictionary', [
            'series' => $series,
            'language' => $language,
            'head_words' => $head_words,
        ]);
    }

    public function eieol_english_meaning_index($series_id, $language_id) {
        $series = EieolSeries::findByIdOrSlug($series_id);
        $language = EieolLanguage::findOrFail($language_id);
        $keywords = [];

        $lessons = EieolLesson::with('glossed_texts.glosses.elements.head_word.language')
            ->where('series_id', '=', $series->id)
            ->where('language_id', '=', $language_id)
            ->select(['id', 'title', 'order'])
            ->orderBy('order')
            ->get();

        //loop through all the lessons, glossed texts and glosses to group like keywords

        foreach ($lessons as $lesson) {
            foreach ($lesson->glossed_texts as $glossed_text) {
                foreach ($glossed_text->glosses as $gloss) {
                    foreach ($gloss->elements as $element) {

                        if (!$element->head_word->keywords) {
                            continue;
                        }

                        $keywordsArray = explode(',', $element->head_word->keywords);
                        foreach ($keywordsArray as $keyword) {
                            $key = "$keyword -- {$element->head_word->word} -- {$element->head_word->definition}";

                            $keywords[$key] = $keywords[$key] ?? [
                                'keyword' => $keyword,
                                'head_word' => $element->head_word,
                                'glossed_text_gloss_ids' => []
                            ];

                            $keywords[$key]['glossed_text_gloss_ids'][$gloss->id] = $lesson;
                        }

                    }
                }
            }
        }

        ksort($keywords);

        return view('eieol_english_meaning_index', [
             'series' => $series,
             'language' => $language,
             'keywords' => $keywords,
         ]);
    }
}
