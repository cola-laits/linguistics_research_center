<?php

class EieolGlossedTextController extends BaseController {	

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
	
		$rules = array(			
				'order' => 'required|integer|unique:eieol_glossed_text,order,null,id,lesson_id,'. Input::get('lesson_id'),
				'glossed_text' => 'required',
				'lesson_id' => 'required|exists:eieol_lesson,id'
		);
		
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
			));
		} else {
			$glossed_text = new EieolGlossedText;
				
			$glossed_text->order = Input::get('order');
			$glossed_text->glossed_text = Input::get('glossed_text');
			$glossed_text->lesson_id = Input::get('lesson_id');
			$glossed_text->created_by = Auth::user()->username;
			$glossed_text->updated_by = Auth::user()->username;
				
			$glossed_text->save();
			return Response::json(array(
					'success' => true,
					'added' => true,
					'action' => '/admin/eieol_glossed_text/' . $glossed_text->id, //sent to turn the create form into an update form
					'message' => 'Glossed Text was successfully added.'
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
				'order' => 'required|integer|unique:eieol_glossed_text,order,' . $id . ',id,lesson_id,'. Input::get('lesson_id'),
				'glossed_text' => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
			));
		} else {
 			$glossed_text = EieolGlossedText::find($id);
			
 			$glossed_text->order = Input::get('order');
			$glossed_text->glossed_text = Input::get('glossed_text');
 			$glossed_text->updated_by = Auth::user()->username;
			
 			$glossed_text->save();
			return Response::json(array(
					'success' => true,
					'message' => 'Glossed Text was successfully updated.'
			));

		}
	}

}
