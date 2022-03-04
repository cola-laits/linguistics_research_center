<?php

namespace App\Http\Controllers;

use App\Models\EieolLanguage;
use App\Models\EieolLesson;
use App\Models\EieolSeries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Normalizer;

Validator::extend('valid_custom_keyboard_layout', function ($field, $value, $parameters) {
    if ($value=='') {
        return true;
    }
    $chars = explode(',', $value); //must be comma separated
    foreach ($chars as $char) {
        $char = trim($char); //don't care about whitespace
        //must start and end with quotes
        if (strpos($char, "'") === false) {
            return false;
        }
        if (strpos($char, "'") != 0) {
            return false;
        }
        if (strrpos($char, "'") != (strlen($char) - 1)) {
            return false;
        }
    }
    return true;
});


class EieolLanguageController extends Controller
{

    public function index() {
        if (Auth::user()->isAdmin()) {
            $languages = EieolLanguage::all()->sortBy('language');
        } else {
            $serieses = Auth::user()->editableSeries->sortBy('order');
            $languages = array();
            foreach ($serieses as $series) {
                foreach ($series->lessons as $lesson) {
                    if (!in_array($lesson->language_id, $languages)) {
                        $languages[] = $lesson->language_id;
                    } //if
                } //for lessons
            } //for series
            $languages = EieolLanguage::whereIn('id', $languages)->get()->sortBy('order');
        } //if amdin

        return view('eieol_language.eieol_language_index', ['languages' => $languages]);
    }

    public function create() {
        return view('eieol_language.eieol_language_form', ['action' => 'Create']);
    }


    public function store(Request $request) {

        $rules = array(
            'language' => 'required',
            'lang_attribute' => 'required',
            'custom_keyboard_layout' => 'valid_custom_keyboard_layout',
        );
        $messages = array(
            'custom_keyboard_layout.valid_custom_keyboard_layout' => 'The keyboard layout must be a comma separated list with each entry quoted.'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/admin2/eieol_language/create')
                ->withErrors($validator->messages())
                ->withInput();
        }

        $language = new EieolLanguage;

        $language->language = $request->get('language');
        $language->custom_keyboard_layout = Normalizer::normalize($request->get('custom_keyboard_layout'), Normalizer::FORM_D);
        $language->custom_sort = Normalizer::normalize($request->get('custom_sort'), Normalizer::FORM_D);
        $language->substitutions = Normalizer::normalize($request->get('substitutions'), Normalizer::FORM_D);
        $language->lang_attribute = $request->get('lang_attribute');
        $language->created_by = Auth::user()->username;
        $language->updated_by = Auth::user()->username;

        $language->save();
        $request->session()->flash('message', $language->language . ' has been created');
        return redirect('/admin2/eieol_language');


    }

    public function edit($id) {
        $language = EieolLanguage::find($id);

        //build list of all chars used by this language
        $chars = array();

        //get all glosses and headwords
        $lessons = EieolLesson::with('glossed_texts.glosses.elements.head_word')
            ->where('language_id', '=', $id)
            ->select(array('id'))
            ->get();

        //loop through all the lessons, glossed texts, glosses and headwords to build list of used chars
        foreach ($lessons as $lesson) {
            foreach ($lesson->glossed_texts as $glossed_text) {
                foreach ($glossed_text->glosses as $gloss) {

                    //clean up surface_form
                    $surface_form = strip_tags($gloss->surface_form); //remove any tags like sup or sub
                    $surface_form = str_replace(' ', '', $surface_form); //replace any whitespace
                    $surface_form = str_replace(',', '', $surface_form); //remove any commas
                    //print '<xmp>' . $surface_form . '</xmp>';

                    $hold_char = '';
                    $len = mb_strlen($surface_form, 'UTF-8') - 1;
                    for ($i = $len; $i >= 0; $i--) { //loop through each code point backwards
                        $code_point = mb_substr($surface_form, $i, 1, 'UTF-8');
                        //print '<xmp>' . $i . ' ' . $code_point . ' ' . preg_match('/\p{Mn}/u', $code_point) . '</xmp>';
                        if (preg_match('/\p{Mn}/u', $code_point)) { //it's a combining mark, save it to add to preceding char
                            $hold_char = $code_point . $hold_char;
                        } else { //regular char
                            //print json_encode($hold_char) . '.';
                            $hold_char = $code_point . $hold_char; //add it to whatever we had before
                            if (!in_array($hold_char, $chars)) { // if we don't already have it, add it to array
                                $chars[] = $hold_char;
                            }
                            $hold_char = ''; //reset to start over
                        } //if combining mark
                    } // loop through surface form's code points

                    //loop through elements to get headwords
                    foreach ($gloss->elements as $element) {
                        //clean up headword
                        $word = $element->head_word->word;
                        $len = mb_strlen($word, 'UTF-8') - 1;
                        $word = mb_substr($word, 1, $len - 1, 'UTF-8'); //remove first and last characters, '<' and '>'
                        $word = strip_tags($word); //remove any tags like sup or sub
                        $word = str_replace(' ', '', $word); //replace any whitespace
                        $word = str_replace(',', '', $word); //remove any commas
                        //print '<xmp>    ' . $word. '</xmp>';

                        $hold_char = '';
                        $len = mb_strlen($word, 'UTF-8') - 1;
                        for ($i = $len; $i >= 0; $i--) { //loop through each code point backwards
                            $code_point = mb_substr($word, $i, 1, 'UTF-8');
                            if (preg_match('/\p{Mn}/u', $code_point)) { //it's a combining mark, save it to add to preceding char
                                $hold_char = $code_point . $hold_char;
                            } else { //regular char
                                //print json_encode($hold_char) . '.';
                                $hold_char = $code_point . $hold_char; //add it to whatever we had before
                                if (!in_array($hold_char, $chars)) { // if we don't already have it, add it to array
                                    $chars[] = $hold_char;
                                }
                                $hold_char = ''; //reset to start over
                            } //if combining mark

                        } //loop through word's code points
                    } //loop through elements
                } //loop through glosses
            } //loop through glossed texts
        } //loop through lessons

        asort($chars);
        return view('eieol_language.eieol_language_form', [
            'language' => $language, 'action' => 'Edit', 'chars' => $chars
        ]);
    }


    public function update(Request $request, $id) {
        $rules = array(
            'language' => 'required',
            'lang_attribute' => 'required',
            'custom_keyboard_layout' => 'valid_custom_keyboard_layout',
        );
        $messages = array(
            'custom_keyboard_layout.valid_custom_keyboard_layout' => 'The keyboard layout must be a comma separated list with each entry quoted.'
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('/admin2/eieol_language/' . $id . '/edit')
                ->withErrors($validator->messages())
                ->withInput();
        }

        $language = EieolLanguage::find($id);

        $language->language = $request->get('language');
        $language->custom_keyboard_layout = Normalizer::normalize($request->get('custom_keyboard_layout'), Normalizer::FORM_D);
        $language->custom_sort = Normalizer::normalize($request->get('custom_sort'), Normalizer::FORM_D);
        $language->substitutions = Normalizer::normalize($request->get('substitutions'), Normalizer::FORM_D);
        $language->lang_attribute = $request->get('lang_attribute');
        $language->updated_by = Auth::user()->username;

        $language->save();

        $request->session()->flash('message', $language->language . ' has been updated');
        return redirect('/admin2/eieol_language');

    }

}
