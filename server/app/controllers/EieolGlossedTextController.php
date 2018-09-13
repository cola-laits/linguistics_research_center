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
			$glossed_text->glossed_text = Normalizer::normalize(Input::get('glossed_text'), Normalizer::FORM_D );
			$glossed_text->lesson_id = Input::get('lesson_id');
			$glossed_text->author_comments = Normalizer::normalize(Input::get('author_comments'), Normalizer::FORM_D );
			$glossed_text->author_done = Input::get('author_done');
			$glossed_text->admin_comments = Normalizer::normalize(Input::get('admin_comments'), Normalizer::FORM_D );
			$glossed_text->created_by = Auth::user()->username;
			$glossed_text->updated_by = Auth::user()->username;
				
			$glossed_text->save();
			return Response::json(array(
					'success' => true,
					'added' => true,
					'action' => '/admin2/eieol_glossed_text/' . $glossed_text->id, //sent to turn the create form into an update form
					'glossed_text_id' => $glossed_text->id,
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
			$text = Normalizer::normalize(Input::get('glossed_text'), Normalizer::FORM_D );	
			$glossed_text->glossed_text = preg_replace('/^<p>(.+?)<\/p>/is','$1',$text); // remove dumb ckeditor paragraph tags			
			$glossed_text->author_comments = Normalizer::normalize(Input::get('author_comments'), Normalizer::FORM_D );
			$glossed_text->author_done = Input::get('author_done');
			$glossed_text->admin_comments = Normalizer::normalize(Input::get('admin_comments'), Normalizer::FORM_D );
 			$glossed_text->updated_by = Auth::user()->username;
			
 			$glossed_text->save();
			return Response::json(array(
					'success' => true,
					'message' => 'Glossed Text was successfully updated.'
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
		EieolGlossedText::find($id)->glosses()->detach();
		EieolGlossedText::destroy($id);
	}

}
