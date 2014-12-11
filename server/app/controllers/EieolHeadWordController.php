<?php

class EieolHeadWordController extends BaseController {	
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//this does NOT list all head words.  It's is a search that returns head words that contain with the url parm "headword"
		//since head words starts with a <, it looks for any matching chars.
		$text = '';
		$head_words = EieolHeadWord::where('word', 'LIKE', '%' . Input::get('head_word') . '%')->take(25)->get()->sortBy('word');
		foreach ($head_words as $head_word) {
			$text .= '<a id="' . $head_word->id . '">' .
					 $head_word->getDisplayHeadWord() .
					 '</a>' .
					 '<br/>';				
		}
		if (count($head_words) == 0) {
			return 'No matching Head Words found';
		} else {
			return $text;
		}
	}
	
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
	
		$rules = array(
			'word' => 'required|regex:/^<.*>$/|unique:eieol_head_word,word,null,id,definition,' . Input::get('definition'), 
			'definition' => 'required',
		);
		$messages = array(
				'word.regex' => 'Word must start with "<" and end with ">"'
		);

		$validator = Validator::make(Input::all(), $rules, $messages);
	
		if ($validator->fails()) {
			return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
			));
		} else {
			$head_word = new EieolHeadWord;
	
			$head_word->word = Input::get('word');
			$head_word->definition = Input::get('definition');
			$head_word->created_by = Auth::user()->username;
			$head_word->updated_by = Auth::user()->username;
	
			$head_word->save();
			
			return Response::json(array(
					'success' => true,
					'added' => true,
					'head_word_id' => $head_word->id,
					'head_word_display' => $head_word->getDisplayHeadWord(),
					'message' => 'Head Word was successfully added.'
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
			'word' => 'required|regex:/^<.*>$/|unique:eieol_head_word,word,' . $id . ',id,definition,' . Input::get('definition'), 
			'definition' => 'required',
		);
		$messages = array(
				'word.regex' => 'Word must start with "<" and end with ">"'
		);
		
		$validator = Validator::make(Input::all(), $rules, $messages);
	
		if ($validator->fails()) {
			return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
			));
		} else {
			$head_word = EieolHeadWord::find($id);
			
			$head_word->word = Input::get('word');
			$head_word->definition = Input::get('definition');
			$head_word->updated_by = Auth::user()->username;
			
			$head_word->save();
			
			return Response::json(array(
					'success' => true,
					'message' => 'Head Word was successfully updated.',
					'head_word_id' => $head_word->id,
					'head_word_display' => $head_word->getDisplayHeadWord(),
			));
	
		}
	}
	
}