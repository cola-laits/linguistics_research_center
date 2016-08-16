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
				
			$grammar->title = Normalizer::normalize(Input::get('title'), Normalizer::FORM_D );
			$grammar->order = Input::get('order');
			$grammar->section_number = Input::get('section_number');
			$grammar->grammar_text = Normalizer::normalize(Input::get('grammar_text'), Normalizer::FORM_D );
			$grammar->lesson_id = Input::get('lesson_id');
			$grammar->author_comments = Normalizer::normalize(Input::get('author_comments'), Normalizer::FORM_D );
			$grammar->author_done = Input::get('author_done');
			$grammar->admin_comments = Normalizer::normalize(Input::get('admin_comments'), Normalizer::FORM_D );
			$grammar->created_by = Auth::user()->username;
			$grammar->updated_by = Auth::user()->username;
				
			$grammar->save();
			return Response::json(array(
					'success' => true,
					'added' => true,
					'grammar_id' => $grammar->id,
					'action' => '/admin2/eieol_grammar/' . $grammar->id, //sent to turn the create form into an update form
					'message' => 'Grammar was successfully added.'
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
			
 			$grammar->title = Normalizer::normalize(Input::get('title'), Normalizer::FORM_D );
 			$grammar->order = Input::get('order');
 			$grammar->section_number = Input::get('section_number');
			$grammar->grammar_text = Normalizer::normalize(Input::get('grammar_text'), Normalizer::FORM_D );
			$grammar->author_comments = Normalizer::normalize(Input::get('author_comments'), Normalizer::FORM_D );
			$grammar->author_done = Input::get('author_done');
			$grammar->admin_comments = Normalizer::normalize(Input::get('admin_comments'), Normalizer::FORM_D );
 			$grammar->updated_by = Auth::user()->username;
			
 			$grammar->save();
			return Response::json(array(
					'success' => true,
					'message' => 'Grammar: ' . $grammar->title . ' was successfully updated.'
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
		EieolGrammar::destroy($id);
	}

}
