<?php

class EieolHeadWordController extends BaseController {	
	

	public function filtered_list()
	{
		//this  is a search that returns head words that contain with the url parm "headword"
		//since head words starts with a <, it looks for any matching chars.
		$text = '';
		$head_words = EieolHeadWord::where('word', 'LIKE', '%' . Normalizer::normalize(Input::get('head_word'), Normalizer::FORM_C ) . '%')
								->where('language_id', '=', Input::get('language') . '%')
								->take(10)->get()->sortBy('word');
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
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$head_word = EieolHeadWord::with('keywords', 'elements', 'etyma')->find($id);
		$return_head_word = $head_word->toArray();	
		
		$glosses = array();
		foreach($head_word->elements as $element){
			if (!in_array($element->gloss->surface_form,$glosses)) {
				$glosses[] = $element->gloss->surface_form;
			}
		}
		sort($glosses);	
		$return_head_word['glosses'] = '';
		foreach($glosses as $gloss) {
			$return_head_word['glosses'] .=  $gloss . ', ';
		}		
		$return_head_word['glosses'] = rtrim($return_head_word['glosses'], ', '); //trim off last comma
		
		$return_head_word['keywords'] = '';
		foreach($head_word->keywords as $keyword) {
			$return_head_word['keywords'] .= $keyword->keyword . ',';
		}
		
		return $return_head_word;
	}
	
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
	
		$rules = array(
				//have to put definition in quotes in case it has a comma in it
			'word' => 'required|regex:/^<.*>$/|unique:eieol_head_word,word,null,id,definition,"' . Normalizer::normalize(Input::get('definition'), Normalizer::FORM_C ) . '"', 
			'definition' => 'required',
			'keywords' => 'required',
			'language_id' => 'required',
		);
		$messages = array(
				'word.unique' => 'This Word/Definition combination already exists',
				'word.regex' => 'Word must start with "<" and end with ">"'
		);

		$validator = Validator::make(Input::all(), $rules, $messages);
	
		if ($validator->fails()) {
			return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
			));
		} else {
			$returned_head_word = DB::transaction(function() {
				$head_word = new EieolHeadWord;
				$head_word->word = Normalizer::normalize(Input::get('word'), Normalizer::FORM_C );
				$head_word->definition = Normalizer::normalize(Input::get('definition'), Normalizer::FORM_C );
				if (Input::get('etyma_id') == '0') {
					$head_word->etyma_id = null;
				} else {
					$head_word->etyma_id = Input::get('etyma_id');
				}
				$head_word->language_id = Input::get('language_id');
				$head_word->created_by = Auth::user()->username;
				$head_word->updated_by = Auth::user()->username;
		
				$head_word->save();
				
				//now deal with keywords
				$keyword_recs = array();
				foreach (explode(',',Input::get('keywords')) as $keyword) {
					$keyword_recs[] = new EieolHeadWordKeyword(array('keyword' => strtoupper($keyword),
																	 'language_id' => Input::get('language_id'),
																	 'created_by' => Auth::user()->username, 
																	 'updated_by' => Auth::user()->username,));
				}
				$head_word->keywords()->saveMany($keyword_recs);
				
				return $head_word;
			}); //end transaction
			
			return Response::json(array(
					'success' => true,
					'added' => true,
					'head_word_id' => $returned_head_word->id,
					'head_word_display' => $returned_head_word->getDisplayHeadWord(),
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
				//have to put definition in quotes in case it has a comma in it
			'word' => 'required|regex:/^<.*>$/|unique:eieol_head_word,word,' . $id . ',id,definition,"' . Normalizer::normalize(Input::get('definition'), Normalizer::FORM_C ) . '"', 
			'definition' => 'required',
		);
		$messages = array(
				'word.unique' => 'This Word/Definition combination already exists',
				'word.regex' => 'Word must start with "<" and end with ">"'
		);
		
		$validator = Validator::make(Input::all(), $rules, $messages);
	
		if ($validator->fails()) {
			return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
			));
		} else {
			$head_word = DB::transaction(function($id) use ($id) {
				$head_word = EieolHeadWord::with('keywords')->find($id);
				$head_word->word = Normalizer::normalize(Input::get('word'), Normalizer::FORM_C );
				if (Input::get('etyma_id') == '0') {
					$head_word->etyma_id = null;
				} else {
					$head_word->etyma_id = Input::get('etyma_id');
				}
				$head_word->definition = Normalizer::normalize(Input::get('definition'), Normalizer::FORM_C );
				$head_word->updated_by = Auth::user()->username;
			
				$head_word->save();
			
				//now deal with keywords
				
				//build list of all keywords sent in
				$input_keywords = array();
				foreach (explode(',',Input::get('keywords')) as $keyword) {
					$input_keywords[] = strtoupper($keyword);
				}
				
				//build list of all keywords on the table, if a word is on file but not in input, delete it
				$table_keywords = array();
				foreach ($head_word->keywords as $keyword) {
					if (!in_array($keyword->keyword, $input_keywords)) {
						$keyword->delete();
					} else {
						$table_keywords[] = $keyword->keyword;
					}
				}
				
				//if a word is in the input but not on file, add it
				foreach($input_keywords as $keyword) {
					if (!in_array($keyword, $table_keywords)) {
						$keyword_rec = new EieolHeadWordKeyword(array('keyword' => $keyword, 
																	  'language_id' => Input::get('language_id'),
																	  'created_by' => Auth::user()->username, 
																	  'updated_by' => Auth::user()->username,));
						$head_word->keywords()->save($keyword_rec);
					}
				}
			
				return $head_word;
			}); //end transaction
			
			return Response::json(array(
					'success' => true,
					'message' => 'Head Word was successfully updated.',
					'head_word_id' => $head_word->id,
					'head_word_display' => $head_word->getDisplayHeadWord(),
			));
	
		}
	}
	
}