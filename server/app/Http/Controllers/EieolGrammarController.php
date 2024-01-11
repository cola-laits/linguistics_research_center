<?php

namespace App\Http\Controllers;

use App\Models\EieolGrammar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Normalizer;

class EieolGrammarController extends Controller
{

    public function store(Request $request) {
        $rules = array(
            'order' => 'required|integer|unique:eieol_grammar,order,null,id,lesson_id,' . $request->get('lesson_id'),
            'section_number' => 'required|unique:eieol_grammar,section_number,null,id,lesson_id,' . $request->get('lesson_id'),
            'title' => 'required|unique:eieol_grammar,title,null,id,lesson_id,' . $request->get('lesson_id'),
            'grammar_text' => 'required',
            'lesson_id' => 'required|exists:eieol_lesson,id'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return [
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ];
        }

        $grammar = new EieolGrammar;

        $grammar->title = Normalizer::normalize($request->get('title'), Normalizer::FORM_C);
        $grammar->order = $request->get('order');
        $grammar->section_number = $request->get('section_number');
        $grammar->grammar_text = Normalizer::normalize($request->get('grammar_text'), Normalizer::FORM_C);
        $grammar->lesson_id = $request->get('lesson_id');

        $grammar->save();
        return [
            'success' => true,
            'added' => true,
            'grammar_id' => $grammar->id,
            'grammar' => $grammar,
            'action' => '/admin2/eieol_grammar/' . $grammar->id, //sent to turn the create form into an update form
            'message' => 'Grammar was successfully added.'
        ];
    }

    public function update(Request $request, $id) {
        $rules = array(
            'order' => 'required|integer|unique:eieol_grammar,order,' . $id . ',id,lesson_id,' . $request->get('lesson_id'),
            'section_number' => 'required|unique:eieol_grammar,section_number,' . $id . ',id,lesson_id,' . $request->get('lesson_id'),
            'title' => 'required|unique:eieol_grammar,title,' . $id . ',id,lesson_id,' . $request->get('lesson_id'),
            'grammar_text' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return [
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ];
        }

        $grammar = EieolGrammar::find($id);

        $grammar->title = Normalizer::normalize($request->get('title'), Normalizer::FORM_C);
        $grammar->order = $request->get('order');
        $grammar->section_number = $request->get('section_number');
        $grammar->grammar_text = Normalizer::normalize($request->get('grammar_text'), Normalizer::FORM_C);

        $grammar->save();
        return [
            'success' => true,
            'grammar' => $grammar,
            'message' => 'Grammar: ' . $grammar->title . ' was successfully updated.'
        ];
    }

    public function destroy($id) {
        EieolGrammar::destroy($id);
    }

}
