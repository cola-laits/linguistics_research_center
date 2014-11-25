<?php

class EieolGrammarController extends BaseController {	

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
	
		$rules = array(			
				'order' => 'required|integer|unique:eieol_grammar,order,null,id,lesson_id,'. Input::get('lesson_id'),
				'section_number' => 'required|unique:eieol_grammar,section_number,null,id,lesson_id,'. Input::get('lesson_id'),
				'title' => 'required|unique:eieol_grammar,title,null,id,lesson_id,'. Input::get('lesson_id'),
				'grammar_text' => 'required',
				'lesson_id' => 'required|exists:eieol_lesson,id'
		);
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
			));
		} else {
			$grammar = new EieolGrammar;
				
			$grammar->title = Input::get('title');
			$grammar->order = Input::get('order');
			$grammar->section_number = Input::get('section_number');
			$grammar->grammar_text = Input::get('grammar_text');
			$grammar->lesson_id = Input::get('lesson_id');
			$grammar->created_by = Auth::user()->username;
			$grammar->updated_by = Auth::user()->username;
				
			$grammar->save();
			return Response::json(array(
					'success' => true,
					'message' => 'Grammar: ' . $grammar->title . ' was successfully updated.'
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
				'order' => 'required|integer|unique:eieol_grammar,order,' . $id . ',id,lesson_id,'. Input::get('lesson_id'),
				'section_number' => 'required|unique:eieol_grammar,section_number,' . $id . ',id,lesson_id,'. Input::get('lesson_id'),
				'title' => 'required|unique:eieol_grammar,title,' . $id . ',id,lesson_id,'. Input::get('lesson_id'),
				'grammar_text' => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
			));
		} else {
 			$grammar = EieolGrammar::find($id);
			
 			$grammar->title = Input::get('title');
 			$grammar->order = Input::get('order');
 			$grammar->section_number = Input::get('section_number');
			$grammar->grammar_text = Input::get('grammar_text');
 			$grammar->updated_by = Auth::user()->username;
			
 			$grammar->save();
			return Response::json(array(
					'success' => true,
					'message' => 'Grammar: ' . $grammar->title . ' was successfully updated.'
			));

		}
	}

}
