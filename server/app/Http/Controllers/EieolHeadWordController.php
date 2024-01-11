<?php

namespace App\Http\Controllers;

use App\Models\EieolHeadWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Normalizer;

class EieolHeadWordController extends Controller
{

    public function store(Request $request) {

        $rules = array(
            //have to put definition in quotes in case it has a comma in it
            'word' => 'required|regex:/^<.*>$/|unique:eieol_head_word,word,null,id,definition,"' . Normalizer::normalize($request->get('definition'), Normalizer::FORM_C) . '",language_id,' . $request->get('language_id'),
            'definition' => 'required',
            'keywords' => 'required',
            'language_id' => 'required',
        );
        $messages = array(
            'word.unique' => 'This Word/Definition combination already exists',
            'word.regex' => 'Word must start with "<" and end with ">"'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return [
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ];
        }

        $head_word = new EieolHeadWord;
        $head_word->word = Normalizer::normalize($request->get('word'), Normalizer::FORM_C);
        $head_word->definition = Normalizer::normalize($request->get('definition'), Normalizer::FORM_C);
        if ($request->get('etyma_id') == '0') {
            $head_word->etyma_id = null;
        } else {
            $head_word->etyma_id = $request->get('etyma_id');
        }
        $head_word->keywords = $request->get('keywords');
        $head_word->language_id = $request->get('language_id');

        $head_word->save();

        return [
            'success' => true,
            'added' => true,
            'head_word_id' => $head_word->id,
            'message' => 'Head Word was successfully added.'
        ];
    }

    public function update(Request $request, $id) {
        $rules = array(
            //have to put definition in quotes in case it has a comma in it
            'word' => 'required|regex:/^<.*>$/|unique:eieol_head_word,word,' . $id . ',id,definition,"' . Normalizer::normalize($request->get('definition'), Normalizer::FORM_C) . '",language_id,' . $request->get('language_id'),
            'definition' => 'required',
        );
        $messages = array(
            'word.unique' => 'This Word/Definition combination already exists',
            'word.regex' => 'Word must start with "<" and end with ">"'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return [
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ];
        }

        $head_word = EieolHeadWord::find($id);
        $head_word->word = Normalizer::normalize($request->get('word'), Normalizer::FORM_C);
        if ($request->get('etyma_id') == '0') {
            $head_word->etyma_id = null;
        } else {
            $head_word->etyma_id = $request->get('etyma_id');
        }
        $head_word->keywords = $request->get('keywords');
        $head_word->definition = Normalizer::normalize($request->get('definition'), Normalizer::FORM_C);

        $head_word->save();

        $head_word = EieolHeadWord::find($id);

        return [
            'success' => true,
            'message' => 'Head Word was successfully updated.',
            'head_word_id' => $head_word->id,
        ];
    }

}
