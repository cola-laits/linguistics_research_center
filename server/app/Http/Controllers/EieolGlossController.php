<?php

namespace App\Http\Controllers;

use App\EieolElement;
use App\EieolGloss;
use App\EieolGlossedText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Normalizer;

class EieolGlossController extends Controller
{

    public function filtered_list(Request $request) {
        //this is a search that returns glosses that start with the url parm "gloss"
        $glosses = EieolGloss::with('elements.head_word')->where('surface_form', 'LIKE', Normalizer::normalize($request->get('gloss'), Normalizer::FORM_C) . '%')
            ->where('language_id', '=', $request->get('language') . '%')
            ->take(15)->orderBy('surface_form')->get()->map(function($g) {
                $v = new \stdClass();
                $v->id = $g['id'];
                $v->html = $g->getDisplayGloss();
                return $v;
            });

        return [
            'glosses'=>$glosses
        ];
    }

    public function show($id) {
        $gloss = EieolGloss::with('elements.head_word', 'glossed_text.lesson')->find($id);
        $return_gloss = $gloss->toArray();

        $i = 0;
        foreach ($gloss->elements as $element) {
            $i++;
            $return_gloss['element_' . $i . '_id'] = $element->id;
            $return_gloss['element_' . $i . '_part_of_speech'] = $element->part_of_speech;
            $return_gloss['element_' . $i . '_analysis'] = $element->analysis;
            $return_gloss['element_' . $i . '_head_word_id'] = $element->head_word_id;
            $return_gloss['element_' . $i . '_head_word_display'] = $element->head_word->getDisplayHeadWord();
            $return_gloss['element_' . $i . '_order'] = $element->order;
        }

        $lessons = array();
        $glossed_text = $gloss->glossed_text;
        if (!in_array($glossed_text->lesson->title, $lessons)) {
            $lessons[] = $glossed_text->lesson->title;
        }
        $return_gloss['lessons'] = '';
        foreach ($lessons as $lesson) {
            $return_gloss['lessons'] .= $lesson . ', ';
        }
        $return_gloss['lessons'] = rtrim($return_gloss['lessons'], ', ');

        return $return_gloss;
    }

    public function store(Request $request) {

        $rules = array(
            'surface_form' => 'required',
            'contextual_gloss' => 'required',
            'language_id' => 'required',
            'element_1_part_of_speech' => 'required',
            'element_1_head_word_id' => 'required|exists:eieol_head_word,id',
            'element_2_part_of_speech' => 'required_with:element_2_head_word_id',
            'element_2_head_word_id' => 'required_with:element_2_part_of_speech',
            'element_3_part_of_speech' => 'required_with:element_3_head_word_id',
            'element_3_head_word_id' => 'required_with:element_3_part_of_speech',
            'element_4_part_of_speech' => 'required_with:element_4_head_word_id',
            'element_4_head_word_id' => 'required_with:element_4_part_of_speech',
            'element_5_part_of_speech' => 'required_with:element_5_head_word_id',
            'element_5_head_word_id' => 'required_with:element_5_part_of_speech',
            'element_6_part_of_speech' => 'required_with:element_6_head_word_id',
            'element_6_head_word_id' => 'required_with:element_6_part_of_speech',
        );
        $messages = array(
            'element_1_part_of_speech.required' => 'The first Part of Speech is required',
            'element_1_head_word_id.required' => 'The first Head Word is required',
            'element_2_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
            'element_2_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
            'element_3_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
            'element_3_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
            'element_4_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
            'element_4_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
            'element_5_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
            'element_5_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
            'element_6_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
            'element_6_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return [
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ];
        }
        $gloss_id = DB::transaction(function () use ($request) {

            $highest_order = EieolGloss::where('glossed_text_id',$request->get('glossed_text_id'))->max('order');
            if (!$highest_order) {
                $highest_order = 0;
            }
            $highest_order += 10;

            $gloss = new EieolGloss;

            $gloss->glossed_text_id = $request->get('glossed_text_id');
            $gloss->order = $highest_order;
            $gloss->surface_form = Normalizer::normalize($request->get('surface_form'), Normalizer::FORM_C);
            $gloss->contextual_gloss = Normalizer::normalize($request->get('contextual_gloss'), Normalizer::FORM_C);
            $gloss->language_id = $request->get('language_id');
            $gloss->comments = Normalizer::normalize($request->get('comments'), Normalizer::FORM_C);
            $gloss->underlying_form = Normalizer::normalize($request->get('underlying_form'), Normalizer::FORM_C);
            $gloss->author_comments = Normalizer::normalize($request->get('author_comments'), Normalizer::FORM_C);
            $gloss->author_done = $request->get('author_done');
            $gloss->admin_comments = Normalizer::normalize($request->get('admin_comments'), Normalizer::FORM_C);
            $gloss->created_by = Auth::user()->username;
            $gloss->updated_by = Auth::user()->username;

            $gloss->save();

            //loop through element elements
            for ($i = 1; $i <= 6; $i++) {
                //store elements
                if ($request->get('element_' . $i . '_part_of_speech')) {
                    $element = new EieolElement;

                    $element->gloss_id = $gloss->id;
                    $element->part_of_speech = $request->get('element_' . $i . '_part_of_speech');
                    $element->analysis = $request->get('element_' . $i . '_analysis');
                    $element->head_word_id = $request->get('element_' . $i . '_head_word_id');
                    $element->order = $i;
                    $element->created_by = Auth::user()->username;
                    $element->updated_by = Auth::user()->username;

                    $element->save();
                }
            }//endfor


            return $gloss->id;
        });//end transaction

        //get it to return full display with head word
        $gloss = EieolGloss::with('elements.head_word')->find($gloss_id);

        return [
            'success' => true,
            'added' => true,
            'gloss_id' => $gloss->id,
            'gloss_display' => $gloss->getDisplayGloss(),
            'message' => 'Gloss was successfully added.',
            'author_comments' => $gloss->author_comments,
            'author_done' => $gloss->author_done,
            'admin_comments' => $gloss->admin_comments,
            'glossed_text'=>EieolGlossedText::with('glosses.language', 'glosses.elements.head_word.language')
                ->where('id', $request->get('glossed_text_id'))
                ->first()
        ];


    }

    public function update(Request $request, $id) {
        $rules = array(
            'surface_form' => 'required',
            'element_1_part_of_speech' => 'required',
            'element_1_head_word_id' => 'required|exists:eieol_head_word,id',
            'element_2_part_of_speech' => 'required_with:element_2_head_word_id',
            'element_2_head_word_id' => 'required_with:element_2_part_of_speech',
            'element_3_part_of_speech' => 'required_with:element_3_head_word_id',
            'element_3_head_word_id' => 'required_with:element_3_part_of_speech',
            'element_4_part_of_speech' => 'required_with:element_4_head_word_id',
            'element_4_head_word_id' => 'required_with:element_4_part_of_speech',
            'element_5_part_of_speech' => 'required_with:element_5_head_word_id',
            'element_5_head_word_id' => 'required_with:element_5_part_of_speech',
            'element_6_part_of_speech' => 'required_with:element_6_head_word_id',
            'element_6_head_word_id' => 'required_with:element_6_part_of_speech',
        );
        $messages = array(
            'element_1_part_of_speech.required' => 'The first Part of Speech is required',
            'element_1_head_word_id.required' => 'The first Head Word is required',
            'element_2_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
            'element_2_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
            'element_3_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
            'element_3_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
            'element_4_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
            'element_4_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
            'element_5_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
            'element_5_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
            'element_6_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
            'element_6_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return [
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ];
        }

        DB::transaction(function () use ($id, $request) {
            $gloss = EieolGloss::with('elements.head_word')->find($id);

            $gloss->surface_form = Normalizer::normalize($request->get('surface_form'), Normalizer::FORM_C);
            $gloss->contextual_gloss = Normalizer::normalize($request->get('contextual_gloss'), Normalizer::FORM_C);
            $gloss->comments = Normalizer::normalize($request->get('comments'), Normalizer::FORM_C);
            $gloss->underlying_form = Normalizer::normalize($request->get('underlying_form'), Normalizer::FORM_C);
            $gloss->author_comments = Normalizer::normalize($request->get('author_comments'), Normalizer::FORM_C);
            $gloss->author_done = $request->get('author_done');
            $gloss->admin_comments = Normalizer::normalize($request->get('admin_comments'), Normalizer::FORM_C);

            $gloss->updated_by = Auth::user()->username;

            $gloss->save();

            //loop through element elements
            for ($i = 1; $i <= 6; $i++) {
                if ($request->get('element_' . $i . '_part_of_speech')) {

                    //decide if we are storing or updating elements
                    if ($request->get('element_' . $i . '_id')) {
                        $element = EieolElement::find($request->get('element_' . $i . '_id'));

                        $element->part_of_speech = $request->get('element_' . $i . '_part_of_speech');
                        $element->analysis = $request->get('element_' . $i . '_analysis');
                        $element->head_word_id = $request->get('element_' . $i . '_head_word_id');
                        $element->updated_by = Auth::user()->username;

                        $element->save();
                    } else {
                        $element = new EieolElement;

                        $element->gloss_id = $gloss->id;
                        $element->part_of_speech = $request->get('element_' . $i . '_part_of_speech');
                        $element->analysis = $request->get('element_' . $i . '_analysis');
                        $element->head_word_id = $request->get('element_' . $i . '_head_word_id');
                        $element->order = $i;
                        $element->created_by = Auth::user()->username;
                        $element->updated_by = Auth::user()->username;

                        $element->save();
                    }
                }
            }//endfor

        }); //end transaction

        //get it again in case they change the headword
        $gloss = EieolGloss::with('elements.head_word')->find($id);

        return [
            'success' => true,
            'message' => 'Gloss was successfully updated.',
            'gloss_id' => $gloss->id,
            'gloss_display' => '<br>' . $gloss->getDisplayGloss(),
            'author_comments' => $gloss->author_comments,
            'author_done' => $gloss->author_done,
            'admin_comments' => $gloss->admin_comments,
            'glossed_text'=>EieolGlossedText::with('glosses.language', 'glosses.elements.head_word.language')
                ->where('id', $request->get('glossed_text_id'))
                ->first()
        ];


    }

}
