<?php

namespace App\Http\Controllers;

use App\Models\EieolGlossedText;
use App\Models\EieolGrammar;
use App\Models\EieolLanguage;
use App\Models\EieolLesson;
use App\Models\EieolSeries;
use App\Models\Issue;
use App\Models\LexEtyma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Normalizer;

class EieolLessonController extends Controller
{

    public function create(Request $request)
    {
        $series = EieolSeries::findOrFail($request->get('series_id'));
        $languages = EieolLanguage::pluck('language', 'id');
        return view('admin.eieol_lesson_create', [
            'series' => $series,
            'languages' => $languages
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'order' => 'required|integer|unique:eieol_lesson,order,null,id,series_id,' . $request->get('series_id'),
            'title' => 'required|unique:eieol_lesson,title,null,id,series_id,' . $request->get('series_id'),
            'language' => 'required',
            'intro_text' => 'required',
            'series_id' => 'required|exists:eieol_series,id'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/admin2/eieol_lesson/create?series_id=' . $request->get('series_id'))
                ->withErrors($validator->messages())
                ->withInput();
        }

        $lesson = EieolLesson::create([
            'title' => Normalizer::normalize($request->get('title'), Normalizer::FORM_C),
            'order' => $request->get('order'),
            'series_id' => $request->get('series_id'),
            'language_id' => $request->get('language'),
            'intro_text' => Normalizer::normalize($request->get('intro_text'), Normalizer::FORM_C),
        ]);

        $request->session()->flash('message', $lesson->title . ' has been created');
        return redirect('/admin2/eieol_lesson/' . $lesson->id . '/edit');
    }

    public function edit($id)
    {
        $lesson = EieolLesson::with(['series', 'language' ,'series.languages'])->findOrFail($id);
        $grammars = EieolGrammar::where('lesson_id', '=', $id)->orderBy('order')->get();
        $glossed_texts = EieolGlossedText::with('glosses.language', 'glosses.elements.head_word.language')
            ->where('lesson_id', '=', $id)->orderBy('order')->get();

        $series_languages = [$lesson->language->lang_attribute . ':' . $lesson->language->language];
        foreach ($lesson->series->languages as $l) {
            $series_languages[] = $l->lang . ':' . $l->display;
        }

        $etymas = LexEtyma::pluck('entry', 'id');

        $related_issues = Issue::where('status', 'open')
            ->where('pointer', 'like', '/lesson/' . $lesson->id . '%')
            ->get();

        return view('admin.eieol_lesson_edit', ['lesson' => $lesson,
            'grammars' => $grammars,
            'glossed_texts' => $glossed_texts,
            'etymas' => $etymas,
            'series_languages' => $series_languages,
            'issues' => $related_issues
        ]);
    }


    public function update(Request $request, $id)
    {
        $rules = [
            'order' => 'required|integer|unique:eieol_lesson,order,' . $id . ',id,series_id,' . $request->get('series_id'),
            'title' => 'required|unique:eieol_lesson,title,' . $id . ',id,series_id,' . $request->get('series_id'),
            'intro_text' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return [
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ];
        }

        $lesson = EieolLesson::findOrFail($id);
        $lesson->update([
            'title' => Normalizer::normalize($request->get('title'), Normalizer::FORM_C),
            'order' => $request->get('order'),
            'intro_text' => Normalizer::normalize($request->get('intro_text'), Normalizer::FORM_C),
        ]);

        return [
            'success' => true,
            'message' => 'Update was successful',
            'language_id' => $request->get('language'),
        ];
    }

    public function update_text(Request $request, $id)
    {
        $lesson = EieolLesson::findOrFail($id);
        $lesson->update([
            'lesson_text' => Normalizer::normalize($request->get('lesson_text'), Normalizer::FORM_C),
        ]);

        return [
            'success' => true,
            'message' => 'Text was updated successfully'
        ];
    }

    public function update_translation(Request $request, $id)
    {
        $lesson = EieolLesson::findOrFail($id);
        $lesson->update([
            'lesson_translation' => Normalizer::normalize($request->get('lesson_translation'), Normalizer::FORM_C),
        ]);

        return [
            'success' => true,
            'message' => 'Translation was updated successfully'
        ];
    }


}
