<?php

namespace App\Http\Controllers;

use App\EieolHeadWord;
use App\EieolHeadWordKeyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Normalizer;

class EieolHeadWordController extends Controller
{


    public function filtered_list(Request $request) {
        //this  is a search that returns head words that contain with the url parm "headword"
        //since head words starts with a <, it looks for any matching chars.
        $text = '';
        $head_words = EieolHeadWord::where('word', 'LIKE', '%' . Normalizer::normalize($request->get('head_word'), Normalizer::FORM_D) . '%')
            ->where('language_id', '=', $request->get('language') . '%')
            ->take(10)->get()->sortBy('word');
        foreach ($head_words as $head_word) {
            $text .= '<a id="' . $head_word->id . '">' .
                $head_word->getDisplayHeadWord() .
                '</a>' .
                '<br/>';
        }
        if (count($head_words) == 0) {
            return 'No matching Head Words found';
        }

        return $text;
    }

    public function show($id) {
        $head_word = EieolHeadWord::with('keywords', 'elements', 'etyma')->find($id);
        $return_head_word = $head_word->toArray();

        $glosses = array();
        foreach ($head_word->elements as $element) {
            if (!in_array($element->gloss->surface_form, $glosses)) {
                $glosses[] = $element->gloss->surface_form;
            }
        }
        sort($glosses);
        $return_head_word['glosses'] = '';
        foreach ($glosses as $gloss) {
            $return_head_word['glosses'] .= $gloss . ', ';
        }
        $return_head_word['glosses'] = rtrim($return_head_word['glosses'], ', '); //trim off last comma

        $return_head_word['keywords'] = '';
        foreach ($head_word->keywords as $keyword) {
            $return_head_word['keywords'] .= $keyword->keyword . ',';
        }

        return $return_head_word;
    }

    public function store(Request $request) {

        $rules = array(
            //have to put definition in quotes in case it has a comma in it
            'word' => 'required|regex:/^<.*>$/|unique:eieol_head_word,word,null,id,definition,"' . Normalizer::normalize($request->get('definition'), Normalizer::FORM_D) . '",language_id,' . $request->get('language_id'),
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

        $returned_head_word = DB::transaction(function () use ($request) {
            $head_word = new EieolHeadWord;
            $head_word->word = Normalizer::normalize($request->get('word'), Normalizer::FORM_D);
            $head_word->definition = Normalizer::normalize($request->get('definition'), Normalizer::FORM_D);
            if ($request->get('etyma_id') == '0') {
                $head_word->etyma_id = null;
            } else {
                $head_word->etyma_id = $request->get('etyma_id');
            }
            $head_word->language_id = $request->get('language_id');
            $head_word->created_by = Auth::user()->username;
            $head_word->updated_by = Auth::user()->username;

            $head_word->save();

            //now deal with keywords
            $keyword_recs = array();
            foreach (explode(',', $request->get('keywords')) as $keyword) {
                $keyword_recs[] = new EieolHeadWordKeyword(array('keyword' => strtoupper($keyword),
                    'language_id' => $request->get('language_id'),
                    'created_by' => Auth::user()->username,
                    'updated_by' => Auth::user()->username,));
            }
            $head_word->keywords()->saveMany($keyword_recs);

            return $head_word;
        }); //end transaction

        return [
            'success' => true,
            'added' => true,
            'head_word_id' => $returned_head_word->id,
            'head_word_display' => $returned_head_word->getDisplayHeadWord(),
            'message' => 'Head Word was successfully added.'
        ];


    }

    public function update(Request $request, $id) {
        $rules = array(
            //have to put definition in quotes in case it has a comma in it
            'word' => 'required|regex:/^<.*>$/|unique:eieol_head_word,word,' . $id . ',id,definition,"' . Normalizer::normalize($request->get('definition'), Normalizer::FORM_D) . '",language_id,' . $request->get('language_id'),
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

        DB::transaction(function () use ($id, $request) {
            $head_word = EieolHeadWord::with('keywords')->find($id);
            $head_word->word = Normalizer::normalize($request->get('word'), Normalizer::FORM_D);
            if ($request->get('etyma_id') == '0') {
                $head_word->etyma_id = null;
            } else {
                $head_word->etyma_id = $request->get('etyma_id');
            }
            $head_word->definition = Normalizer::normalize($request->get('definition'), Normalizer::FORM_D);
            $head_word->updated_by = Auth::user()->username;

            $head_word->save();

            //now deal with keywords

            //build list of all keywords sent in
            $input_keywords = array();
            foreach (explode(',', $request->get('keywords')) as $keyword) {
                $input_keywords[] = strtoupper($keyword);
            }

            //build list of all keywords on the table, if a word is on file but not in input, delete it
            $table_keywords = array();
            foreach ($head_word->keywords as $keyword) {
                if (!in_array($keyword->keyword, $input_keywords)) {
                    $keyword->delete();
                } else {
                    $table_keywords[] = $keyword->keyword;
                }
            }

            //if a word is in the input but not on file, add it
            foreach ($input_keywords as $keyword) {
                if (!in_array($keyword, $table_keywords)) {
                    $keyword_rec = new EieolHeadWordKeyword(array('keyword' => $keyword,
                        'language_id' => $request->get('language_id'),
                        'created_by' => Auth::user()->username,
                        'updated_by' => Auth::user()->username,));
                    $head_word->keywords()->save($keyword_rec);
                }
            }
        }); //end transaction

        $head_word = EieolHeadWord::with('keywords')->find($id);


        return [
            'success' => true,
            'message' => 'Head Word was successfully updated.',
            'head_word_id' => $head_word->id,
            'head_word_display' => $head_word->getDisplayHeadWord(),
        ];
    }

}
