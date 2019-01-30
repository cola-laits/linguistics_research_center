<?php

namespace App\Http\Controllers;

class EieolGlossedTextGlossController extends Controller {

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
	
		$rules = array(			
				'order' => 'required|integer|unique:eieol_glossed_text_gloss,order,null,id,glossed_text_id,'. Input::get('glossed_text_id'),
				'glossed_text_id' => 'required|exists:eieol_glossed_text,id',
				'gloss_id' => 'required|exists:eieol_gloss,id'
		);
	
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			$msg = '';
			foreach($validator->getMessageBag()->toArray() as $key=>$value) {
				$msg .= $value[0] . ' ';
			}
			return Response::json(array(
					'fail' => true,
					'msg' => $msg
			));
		} else {
			$glossed_text_gloss = new EieolGlossedTextGloss;
			$glossed_text_gloss->order = Input::get('order');
			$glossed_text_gloss->glossed_text_id = Input::get('glossed_text_id');
			$glossed_text_gloss->gloss_id = Input::get('gloss_id');
			$glossed_text_gloss->created_by = Auth::user()->username;
			$glossed_text_gloss->updated_by = Auth::user()->username;
				
			$glossed_text_gloss->save();
			
			//now get it so we can return gloss and headword
			$glossed_text_gloss = EieolGlossedTextGloss::with('gloss')->find($glossed_text_gloss->id);
			return Response::json(array(
					'success' => true,
					'id' => $glossed_text_gloss->id,
			));
		
		}
	
	}
				
				
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

		$rules = array(			
				'order' => 'required|integer|unique:eieol_glossed_text_gloss,order,' . $id . ',id,glossed_text_id,'. Input::get('glossed_text_id')
		);
		
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
			));
		} else {
 			$glossed_text_gloss = EieolGlossedTextGloss::find($id);
			
 			$glossed_text_gloss->order = Input::get('order');
 			$glossed_text_gloss->updated_by = Auth::user()->username;
			
 			$glossed_text_gloss->save();
			return Response::json(array(
					'success' => true,
					'message' => 'Gloss order was successfully updated.',
			));

		}
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		EieolGlossedTextGloss::destroy($id);
	}

}
