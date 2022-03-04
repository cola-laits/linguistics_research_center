<?php

namespace App\Http\Controllers;

use App\Models\EieolGloss;
use App\Models\EieolGlossedText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/*
 * This controller is a remnant of a time when there was an 'eieol_glossed_text_gloss'
 * table between eieol_gloss and eieol_glossed_text, providing a many-to-many relationship
 * between them.  That changed into a one-to-many relationship in April 2019, but we
 * didn't completely rewrite the API to account for that.  'order' and 'glossed_text_id',
 * the two fields that used to be on eieol_glossed_text_gloss, are now on eieol_gloss,
 * but they're still treated as a separate type of thing in the API.
 */
class EieolGlossedTextGlossController extends Controller
{

    public function store(Request $request) {
        $rules = array(
            'order' => 'required|integer|unique:eieol_glossed_text_gloss,order,null,id,glossed_text_id,' . $request->get('glossed_text_id'),
            'glossed_text_id' => 'required|exists:eieol_glossed_text,id',
            'gloss_id' => 'required|exists:eieol_gloss,id'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $msg = '';
            foreach ($validator->getMessageBag()->toArray() as $key => $value) {
                $msg .= $value[0] . ' ';
            }
            return [
                'fail' => true,
                'msg' => $msg
            ];
        }
        $gloss = EieolGloss::findOrFail($request->get('gloss_id'));
        $gloss->order = $request->get('order');
        $gloss->glossed_text_id = $request->get('glossed_text_id');
        $gloss->updated_by = Auth::user()->username;

        $gloss->save();

        return [
            'success' => true,
            'gloss'=>$gloss
        ];
    }

    public function update(Request $request, $id) {
        $rules = array(
            'order' => 'required|integer|unique:eieol_gloss,order,' . $id . ',id,glossed_text_id,' . $request->get('glossed_text_id')
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return [
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ];
        }
        $gloss = EieolGloss::findOrFail($id);

        $gloss->order = $request->get('order');
        $gloss->updated_by = Auth::user()->username;

        $gloss->save();
        return [
            'success' => true,
            'message' => 'Gloss order was successfully updated.',
        ];
    }

    public function destroy($id) {
        $gloss = EieolGloss::findOrFail($id);
        $gloss->glossed_text_id = null;
        $gloss->order = null;
        $gloss->updated_by = Auth::user()->username;
        $gloss->save();
    }

    public function postCopyGloss(Request $request) {
        $gloss_order = EieolGloss::where('glossed_text_id', $request->get('glossed_text_id'))
            ->max('order')
            + 10;

        $gloss = EieolGloss::findOrFail($request->get('existing_gloss_id'));
        $new_gloss_id = $gloss->deepCopy();
        $new_gloss = EieolGloss::findOrFail($new_gloss_id);
        $new_gloss->glossed_text_id = $request->get('glossed_text_id');
        $new_gloss->order = $gloss_order;
        $new_gloss->save();
        return [
            'success'=>true,
            'gloss_id'=>$new_gloss_id,
            'glossed_text'=>EieolGlossedText::with('glosses.language', 'glosses.elements.head_word.language')
                ->where('id', $request->get('glossed_text_id'))
                ->first()
        ];
    }

}
