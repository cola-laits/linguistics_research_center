<?php

namespace App\Http\Controllers;

use App\EieolGlossedTextGloss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        $glossed_text_gloss = new EieolGlossedTextGloss;
        $glossed_text_gloss->order = $request->get('order');
        $glossed_text_gloss->glossed_text_id = $request->get('glossed_text_id');
        $glossed_text_gloss->gloss_id = $request->get('gloss_id');
        $glossed_text_gloss->created_by = Auth::user()->username;
        $glossed_text_gloss->updated_by = Auth::user()->username;

        $glossed_text_gloss->save();

        //now get it so we can return gloss and headword
        $glossed_text_gloss = EieolGlossedTextGloss::with('gloss')->find($glossed_text_gloss->id);
        return [
            'success' => true,
            'id' => $glossed_text_gloss->id,
        ];
    }

    public function update(Request $request, $id) {
        $rules = array(
            'order' => 'required|integer|unique:eieol_glossed_text_gloss,order,' . $id . ',id,glossed_text_id,' . $request->get('glossed_text_id')
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return [
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ];
        }
        $glossed_text_gloss = EieolGlossedTextGloss::find($id);

        $glossed_text_gloss->order = $request->get('order');
        $glossed_text_gloss->updated_by = Auth::user()->username;

        $glossed_text_gloss->save();
        return [
            'success' => true,
            'message' => 'Gloss order was successfully updated.',
        ];
    }

    public function destroy($id) {
        EieolGlossedTextGloss::destroy($id);
    }

}
