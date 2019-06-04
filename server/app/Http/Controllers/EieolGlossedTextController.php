<?php

namespace App\Http\Controllers;

use App\EieolGloss;
use App\EieolGlossedText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Normalizer;

class EieolGlossedTextController extends Controller
{

    public function store(Request $request) {

        $rules = array(
            'order' => 'required|integer|unique:eieol_glossed_text,order,null,id,lesson_id,' . $request->get('lesson_id'),
            'glossed_text' => 'required',
            'lesson_id' => 'required|exists:eieol_lesson,id'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return [
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ];
        }

        $glossed_text = new EieolGlossedText;

        $glossed_text->order = $request->get('order');
        $text = Normalizer::normalize($request->get('glossed_text'), Normalizer::FORM_C);
        $glossed_text->glossed_text = preg_replace('/^<p>(.+?)<\/p>/is', '$1', $text); // remove dumb ckeditor paragraph tags
        $glossed_text->lesson_id = $request->get('lesson_id');
        $glossed_text->author_comments = Normalizer::normalize($request->get('author_comments'), Normalizer::FORM_C);
        $glossed_text->author_done = $request->get('author_done');
        $glossed_text->admin_comments = Normalizer::normalize($request->get('admin_comments'), Normalizer::FORM_C);
        $glossed_text->audio_url = $request->get('audio_url');
        $glossed_text->created_by = Auth::user()->username;
        $glossed_text->updated_by = Auth::user()->username;

        $glossed_text->save();
        return [
            'success' => true,
            'added' => true,
            'action' => '/admin2/eieol_glossed_text/' . $glossed_text->id, //sent to turn the create form into an update form
            'glossed_text_id' => $glossed_text->id,
            'message' => 'Glossed Text was successfully added.'
        ];
    }

    public function update(Request $request, $id) {
        $rules = array(
            'order' => 'required|integer|unique:eieol_glossed_text,order,' . $id . ',id,lesson_id,' . $request->get('lesson_id'),
            'glossed_text' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return [
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ];
        }

        $glossed_text = EieolGlossedText::find($id);

        $glossed_text->order = $request->get('order');
        $text = Normalizer::normalize($request->get('glossed_text'), Normalizer::FORM_C);
        $glossed_text->glossed_text = preg_replace('/^<p>(.+?)<\/p>/is', '$1', $text); // remove dumb ckeditor paragraph tags
        $glossed_text->author_comments = Normalizer::normalize($request->get('author_comments'), Normalizer::FORM_C);
        $glossed_text->author_done = $request->get('author_done');
        $glossed_text->admin_comments = Normalizer::normalize($request->get('admin_comments'), Normalizer::FORM_C);
        $glossed_text->audio_url = $request->get('audio_url');
        $glossed_text->updated_by = Auth::user()->username;

        $glossed_text->save();
        return [
            'success' => true,
            'message' => 'Glossed Text was successfully updated.'
        ];
    }

    public function destroy($id): void {
        foreach (EieolGloss::where('glossed_text_id',$id)->get() as $gloss) {
            $gloss->order = null;
            $gloss->glossed_text_id = null;
            $gloss->save();
        }
        EieolGlossedText::destroy($id);
    }

}
