<?php

class EieolGrammarController extends BaseController {	


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		log::error('grmmar');
		$rules = array(			
				'order' => 'required|integer|unique:eieol_grammar,order,' . $id . ',id,lesson_id,'. Input::get('lesson_id'),
				'section_number' => 'required|unique:eieol_grammar,section_number,' . $id . ',id,lesson_id,'. Input::get('lesson_id'),
				'title' => 'required|unique:eieol_grammar,title,' . $id . ',id,lesson_id,'. Input::get('lesson_id'),
				'grammar_text' => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			log::error('fail');
			return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
			));
		} else {
			log::error('update');
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
