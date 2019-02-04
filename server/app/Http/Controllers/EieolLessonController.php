<?php

namespace App\Http\Controllers;

use App\EieolGlossedText;
use App\EieolGrammar;
use App\EieolLanguage;
use App\EieolLesson;
use App\EieolSeries;
use App\LexEtyma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Normalizer;

class EieolLessonController extends Controller
{

    public function create(Request $request) {
        $series = EieolSeries::find($request->get('series_id'));
        $languages = EieolLanguage::all()->mapWithKeys(function ($lang) {
            return [$lang['id'] => $lang['language']];
        });
        return view('eieol_lesson.eieol_lesson_create', [
            'series' => $series,
            'languages' => $languages
        ]);
    }

    public function store(Request $request) {

        $rules = array(
            'order' => 'required|integer|unique:eieol_lesson,order,null,id,series_id,' . $request->get('series_id'),
            'title' => 'required|unique:eieol_lesson,title,null,id,series_id,' . $request->get('series_id'),
            'language' => 'required',
            'intro_text' => 'required',
            'series_id' => 'required|exists:eieol_series,id'
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/admin2/eieol_lesson/create?series_id=' . $request->get('series_id'))
                ->withErrors($validator->messages())
                ->withInput();
        }

        $lesson = new EieolLesson;

        $lesson->title = \Normalizer::normalize($request->get('title'), Normalizer::FORM_D);
        $lesson->order = $request->get('order');
        $lesson->series_id = $request->get('series_id');
        $lesson->language_id = $request->get('language');
        $lesson->intro_text = \Normalizer::normalize($request->get('intro_text'), Normalizer::FORM_D);
        $lesson->created_by = Auth::user()->username;
        $lesson->updated_by = Auth::user()->username;

        $lesson->save();
        $request->session()->flash('message', $lesson->title . ' has been created');
        return redirect('/admin2/eieol_lesson/' . $lesson->id . '/edit');


    }

    public function edit($id) {
        $lesson = EieolLesson::with('series', 'language')->find($id);
        $lesson->intro_text = htmlentities($lesson->intro_text);
        $lesson->lesson_translation = htmlentities($lesson->lesson_translation);

        $grammars = EieolGrammar::where('lesson_id', '=', $id)->get()->sortBy('order');
        foreach ($grammars as $g) {
            $g->grammar_text = htmlentities($g->grammar_text);
        }

        $glossed_texts = EieolGlossedText::with('glosses.language', 'glosses.elements.head_word.language')->where('lesson_id', '=', $id)->get()->sortBy('order');
        foreach ($glossed_texts as $g) {
            $g->glossed_text = htmlentities($g->glossed_text);
        }

        $series = EieolSeries::with('languages')->find($lesson->series_id);

        $series_languages = array();

        $series_languages[] = $lesson->language->lang_attribute . ':' . $lesson->language->language;

        foreach ($series->languages as $l) {
            $series_languages[] = $l->lang . ':' . $l->display;
        }

        //get languages for pulldown
        $languages = EieolLanguage::all()->mapWithKeys(function ($lang) {
            return [$lang['id'] => $lang['language']];
        });

        //get etymas for pulldown
        $etymas = LexEtyma::all()->mapWithKeys(function ($lang) {
            return [$lang['id'] => $lang['entry']];
        });

        return view('eieol_lesson.eieol_lesson_edit', ['lesson' => $lesson,
            'grammars' => $grammars,
            'glossed_texts' => $glossed_texts,
            'languages' => $languages,
            'etymas' => $etymas,
            'series_languages' => $series_languages]);
    }


    public function update(Request $request, $id) {
        $rules = array(
            'order' => 'required|integer|unique:eieol_lesson,order,' . $id . ',id,series_id,' . $request->get('series_id'),
            'title' => 'required|unique:eieol_lesson,title,' . $id . ',id,series_id,' . $request->get('series_id'),
            'language' => 'required',
            'intro_text' => 'required',
            'series_id' => 'required|exists:eieol_series,id'
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return [
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ];
        }

        DB::transaction(function () use ($id, $request) {
            $lesson = EieolLesson::find($id);

            $language_updated = false;
            //if they change the language, we have to sweep all the glosses, head words and keywords
            if ($lesson->language_id != $request->get('language')) {
                $language_updated = true;
                $glossed_texts = EieolGlossedText::with('glosses.elements.head_word.keywords')->where('lesson_id', '=', $id)->get();
                foreach ($glossed_texts as $glossed_text) {
                    foreach ($glossed_text->glosses as $gloss) {
                        $gloss->language_id = $request->get('language');
                        $gloss->save();
                        foreach ($gloss->elements as $element) {
                            $element->head_word->language_id = $request->get('language');
                            $element->head_word->save();
                            foreach ($element->head_word->keywords as $keyword) {
                                $keyword->language_id = $request->get('language');
                                $keyword->save();
                            }
                        }
                    }
                }
            }

            $lesson->title = Normalizer::normalize($request->get('title'), Normalizer::FORM_D);
            $lesson->order = $request->get('order');
            $lesson->intro_text = Normalizer::normalize($request->get('intro_text'), Normalizer::FORM_D);
            $lesson->language_id = $request->get('language');
            $lesson->author_comments = Normalizer::normalize($request->get('author_comments'), Normalizer::FORM_D);
            $lesson->author_done = $request->get('author_done');
            $lesson->admin_comments = Normalizer::normalize($request->get('admin_comments'), Normalizer::FORM_D);
            $lesson->updated_by = Auth::user()->username;
            $lesson->save();
        });

        return [
            'success' => true,
            'message' => 'Update was successful',
            'language_id' => $request->get('language'),
        ];


    }


    public function update_translation(Request $request, $id) {
        $lesson = EieolLesson::find($id);

        $lesson->lesson_translation = Normalizer::normalize($request->get('lesson_translation'), Normalizer::FORM_D);
        $lesson->translation_author_comments = Normalizer::normalize($request->get('translation_author_comments'), Normalizer::FORM_D);
        $lesson->translation_author_done = $request->get('translation_author_done');
        $lesson->translation_admin_comments = Normalizer::normalize($request->get('translation_admin_comments'), Normalizer::FORM_D);
        $lesson->updated_by = Auth::user()->username;

        $lesson->save();
        return [
            'success' => true,
            'message' => 'Translation was udated successfully'
        ];
    }


}
