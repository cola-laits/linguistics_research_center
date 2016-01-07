<?php

Validator::extend('unique_gloss', function($field,$value,$parameters){
	//this validator attaches to the surface form
	//parameter 0 is the $id of the gloss (can be null)
	//parameter 1 is contextual_gloss
	//parameter 2 is language_id	
	//parameter 3 is the element_1_part_of_speech
	//parameter 4 is the element_1_analysis
	//parameter 5 is the element_1_head_word_id
	
	if ($parameters[0] == null) {
		$glosses = EieolGloss::where('surface_form', '=', Normalizer::normalize($value, Normalizer::FORM_C ))
		->where('contextual_gloss', '=', Normalizer::normalize($parameters[1], Normalizer::FORM_C ))
		->where('language_id', '=', $parameters[2])
		->get();
	} else {
		$glosses = EieolGloss::where('surface_form', '=', Normalizer::normalize($value, Normalizer::FORM_C ))
		->where('contextual_gloss', '=', Normalizer::normalize($parameters[1], Normalizer::FORM_C ))
		->where('language_id', '=', $parameters[2])
		->where('id', '!=', $parameters[0])
		->get();
	}
	foreach($glosses as $gloss) {
		$count = EieolElement::where('gloss_id', '=', $gloss->id)
		->where('part_of_speech', '=', $parameters[3])
		->where('analysis', '=', $parameters[4])
		->where('head_word_id', '=', $parameters[5])
		->count();
		if ($count > 0) {
			return false;
		}
	}
	return true;
});

class EieolGlossController extends BaseController {	
	
	public function filtered_list()
	{
		//this is a search that returns glosses that start with the url parm "gloss"
		$text = '';
		$glosses = EieolGloss::with('elements.head_word')->where('surface_form', 'LIKE', Normalizer::normalize(Input::get('gloss'), Normalizer::FORM_C ) . '%')
												->where('language_id', '=', Input::get('language') . '%')
												->take(15)->get()->sortBy('surface_form');
		foreach ($glosses as $gloss) {
			$text .= '<a id="' . $gloss->id . '">' .
					 $gloss->getDisplayGloss() .
					 '</a>' .
					 '<br/>';				
		}
		if (count($glosses) == 0) {
			return 'No matching glosses found';
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
		$gloss = EieolGloss::with('elements.head_word', 'glossed_texts.lesson')->find($id);
		$return_gloss = $gloss->toArray();
		
		$i=0;
		foreach($gloss->elements as $element){
			$i++;
			$return_gloss['element_' . $i . '_id'] = $element->id;
			$return_gloss['element_' . $i . '_part_of_speech'] = $element->part_of_speech;
			$return_gloss['element_' . $i . '_analysis'] = $element->analysis;
			$return_gloss['element_' . $i . '_head_word_id'] = $element->head_word_id;
			$return_gloss['element_' . $i . '_head_word_display'] = $element->head_word->getDisplayHeadWord();
			$return_gloss['element_' . $i . '_order'] = $element->order;
		}
		
 		$lessons = array();
 		foreach($gloss->glossed_texts as $glossed_text){
 			if (!in_array($glossed_text->lesson->title,$lessons)) {
 				$lessons[] = $glossed_text->lesson->title;
 			}
		}
		$return_gloss['lessons'] = '';
		foreach($lessons as $lesson) {
			$return_gloss['lessons'] .=  $lesson . ', ';
		}
		$return_gloss['lessons'] = rtrim($return_gloss['lessons'], ', ');
		
		return $return_gloss;
	}
	
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
	
		$rules = array(
			'surface_form' => 'required|unique_gloss:' . null . ',' .
														 Input::get("contextual_gloss") . ',' .
														 Input::get("language_id") . ',' .
														 Input::get("element_1_part_of_speech") . ',' .
														 Input::get("element_1_analysis") . ',' .
														 Input::get("element_1_head_word_id"),
			'contextual_gloss' => 'required',
			'language_id' => 'required',
			'element_1_part_of_speech' => 'required',
			'element_1_head_word_id' => 'required|exists:eieol_head_word,id',
			'element_2_part_of_speech' => 'required_with:element_2_head_word_id',
			'element_2_head_word_id' => 'required_with:element_2_part_of_speech',
			'element_3_part_of_speech' => 'required_with:element_3_head_word_id',
			'element_3_head_word_id' => 'required_with:element_3_part_of_speech',
			'element_4_part_of_speech' => 'required_with:element_4_head_word_id',
			'element_4_head_word_id' => 'required_with:element_4_part_of_speech',
			'element_5_part_of_speech' => 'required_with:element_5_head_word_id',
			'element_5_head_word_id' => 'required_with:element_5_part_of_speech',
			'element_6_part_of_speech' => 'required_with:element_6_head_word_id',
			'element_6_head_word_id' => 'required_with:element_6_part_of_speech',
		);
		$messages = array(
				'surface_form.unique_gloss' => 'This Surface Form/Part of Speech/Analysis/Head Word/Contextual Gloss combination already exists',
				'element_1_part_of_speech.required' => 'The first Part of Speech is required',
				'element_1_head_word_id.required' => 'The first Head Word is required',
				'element_2_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
				'element_2_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
				'element_3_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
				'element_3_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
				'element_4_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
				'element_4_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
				'element_5_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
				'element_5_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
				'element_6_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
				'element_6_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
		);

		$validator = Validator::make(Input::all(), $rules, $messages);
	
		if ($validator->fails()) {
			return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
			));
		} else {
			$gloss_id = DB::transaction(function() {
				$gloss = new EieolGloss;
		
				$gloss->surface_form = Normalizer::normalize(Input::get('surface_form'), Normalizer::FORM_C );
				$gloss->contextual_gloss = Normalizer::normalize(Input::get('contextual_gloss'), Normalizer::FORM_C );
				$gloss->language_id = Input::get('language_id');
				$gloss->comments = Normalizer::normalize(Input::get('comments'), Normalizer::FORM_C );
				$gloss->underlying_form = Normalizer::normalize(Input::get('underlying_form'), Normalizer::FORM_C );
				$gloss->author_comments = Normalizer::normalize(Input::get('author_comments'), Normalizer::FORM_C );
				$gloss->author_done = Input::get('author_done');
				$gloss->admin_comments = Normalizer::normalize(Input::get('admin_comments'), Normalizer::FORM_C );
				$gloss->created_by = Auth::user()->username;
				$gloss->updated_by = Auth::user()->username;
		
				$gloss->save();
				
				//loop through element elements
				for ($i = 1; $i <= 6; $i++) {
					//store elements
					if (Input::has('element_' . $i . '_part_of_speech')){
						$element = new EieolElement;
						
						$element->gloss_id = $gloss->id;
						$element->part_of_speech = Input::get('element_' . $i . '_part_of_speech');
						$element->analysis = Input::get('element_' . $i . '_analysis');
						$element->head_word_id = Input::get('element_' . $i . '_head_word_id');
						$element->order = $i;
						$element->created_by = Auth::user()->username;
						$element->updated_by = Auth::user()->username;
						
						$element->save();
					
						//add part of speech if new
						if (EieolPartOfSpeech::where('part_of_speech', '=', Input::get('element_' . $i . '_part_of_speech'))
											 ->where('language_id', '=', Input::get('language_id'))
											 ->count() == 0 ) {
							$part_of_speech = new EieolPartOfSpeech;
							$part_of_speech->part_of_speech = Input::get('element_' . $i . '_part_of_speech');
							$part_of_speech->language_id = Input::get('language_id');
							$part_of_speech->created_by = Auth::user()->username;
							$part_of_speech->updated_by = Auth::user()->username;
							$part_of_speech->save();
						}
							
						//add analysis if new
						if (Input::has('element_' . $i . '_analysis')) {
							if (EieolAnalysis::where('analysis', '=', Input::get('element_' . $i . '_analysis'))
											 ->where('language_id', '=', Input::get('language_id'))
											 ->count() == 0 ) {
								$analysis = new EieolAnalysis;
								$analysis->analysis = Input::get('element_' . $i . '_analysis');
								$analysis->language_id = Input::get('language_id');
								$analysis->created_by = Auth::user()->username;
								$analysis->updated_by = Auth::user()->username;
								$analysis->save();
							}
						}
					}
				}//endfor
				
				
				return $gloss->id;
			});//end transaction
			
			//get it to return full display with head word
			$gloss = EieolGloss::with('elements.head_word')->find($gloss_id);
			
			return Response::json(array(
					'success' => true,
					'added' => true,
					'gloss_id' => $gloss->id,
					'gloss_display' => $gloss->getDisplayGloss(),
					'message' => 'Gloss was successfully added.',
					'author_comments' => $gloss->author_comments,
					'author_done' => $gloss->author_done,
					'admin_comments' => $gloss->admin_comments
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
				'surface_form' => 'required|unique_gloss:' . $id . ',' .
														 	 Input::get("contextual_gloss") . ',' .
														 	 Input::get("language_id") . ',' .
															 Input::get("element_1_part_of_speech") . ',' .
															 Input::get("element_1_analysis") . ',' .
															 Input::get("element_1_head_word_id"),
				'contextual_gloss' => 'required',
				'element_1_part_of_speech' => 'required',
				'element_1_head_word_id' => 'required|exists:eieol_head_word,id',
				'element_2_part_of_speech' => 'required_with:element_2_head_word_id',
				'element_2_head_word_id' => 'required_with:element_2_part_of_speech',
				'element_3_part_of_speech' => 'required_with:element_3_head_word_id',
				'element_3_head_word_id' => 'required_with:element_3_part_of_speech',
				'element_4_part_of_speech' => 'required_with:element_4_head_word_id',
				'element_4_head_word_id' => 'required_with:element_4_part_of_speech',
				'element_5_part_of_speech' => 'required_with:element_5_head_word_id',
				'element_5_head_word_id' => 'required_with:element_5_part_of_speech',
				'element_6_part_of_speech' => 'required_with:element_6_head_word_id',
				'element_6_head_word_id' => 'required_with:element_6_part_of_speech',
		);
		$messages = array(
				'surface_form.unique_gloss' => 'This Surface Form/Part of Speech/Analysis/Head Word/Contextual Gloss combination already exists',
				'element_1_part_of_speech.required' => 'The first Part of Speech is required',
				'element_1_head_word_id.required' => 'The first Head Word is required',
				'element_2_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
				'element_2_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
				'element_3_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
				'element_3_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
				'element_4_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
				'element_4_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
				'element_5_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
				'element_5_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
				'element_6_part_of_speech.required_with' => 'Since you picked a Head Word, you must enter a Part of Speech',
				'element_6_head_word_id.required_with' => 'Since you entered a Part of Speech, you must pick a Head Word',
		);
		
		$validator = Validator::make(Input::all(), $rules,$messages);
	
		if ($validator->fails()) {
			return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
			));
		} else {	
			DB::transaction(function($id) use ($id) {
				$gloss = EieolGloss::with('elements.head_word')->find($id);
				
				$gloss->surface_form = Normalizer::normalize(Input::get('surface_form'), Normalizer::FORM_C );
				$gloss->contextual_gloss = Normalizer::normalize(Input::get('contextual_gloss'), Normalizer::FORM_C );
				$gloss->comments = Normalizer::normalize(Input::get('comments'), Normalizer::FORM_C );
				$gloss->underlying_form = Normalizer::normalize(Input::get('underlying_form'), Normalizer::FORM_C );
				$gloss->author_comments = Normalizer::normalize(Input::get('author_comments'), Normalizer::FORM_C );
				$gloss->author_done = Input::get('author_done');
				$gloss->admin_comments = Normalizer::normalize(Input::get('admin_comments'), Normalizer::FORM_C );
				
				$gloss->updated_by = Auth::user()->username;
				
				$gloss->save();
				
				//loop through element elements
				for ($i = 1; $i <= 6; $i++) {
					if (Input::has('element_' . $i . '_part_of_speech')){
						
						//decide if we are storing or updating elements
						if (Input::has('element_' . $i . '_id')) {
							$element = EieolElement::find(Input::get('element_' . $i . '_id'));
								
							$element->part_of_speech = Input::get('element_' . $i . '_part_of_speech');
							$element->analysis = Input::get('element_' . $i . '_analysis');
							$element->head_word_id = Input::get('element_' . $i . '_head_word_id');
							$element->updated_by = Auth::user()->username;
								
							$element->save();
						} else {
							$element = new EieolElement;
					
							$element->gloss_id = $gloss->id;
							$element->part_of_speech = Input::get('element_' . $i . '_part_of_speech');
							$element->analysis = Input::get('element_' . $i . '_analysis');
							$element->head_word_id = Input::get('element_' . $i . '_head_word_id');
							$element->order = $i;
							$element->created_by = Auth::user()->username;
							$element->updated_by = Auth::user()->username;
					
							$element->save();
						} 
							
						//add part of speech if new
						if (EieolPartOfSpeech::where('part_of_speech', '=', Input::get('element_' . $i . '_part_of_speech'))
											 ->where('language_id', '=', Input::get('language_id'))
								    		 ->count() == 0 ) {
							$part_of_speech = new EieolPartOfSpeech;
							$part_of_speech->part_of_speech = Input::get('element_' . $i . '_part_of_speech');
							$part_of_speech->language_id = Input::get('language_id');
							$part_of_speech->created_by = Auth::user()->username;
							$part_of_speech->updated_by = Auth::user()->username;
							$part_of_speech->save();
						}
							
						//add analysis if new
						if (Input::has('element_' . $i . '_analysis')) {
							if (EieolAnalysis::where('analysis', '=', Input::get('element_' . $i . '_analysis'))
											 ->where('language_id', '=', Input::get('language_id'))
									 		 ->count() == 0 ) {
								$analysis = new EieolAnalysis;
								$analysis->analysis = Input::get('element_' . $i . '_analysis');
								$analysis->language_id = Input::get('language_id');
								$analysis->created_by = Auth::user()->username;
								$analysis->updated_by = Auth::user()->username;
								$analysis->save();
							}
						}
					}
				}//endfor
				
			}); //end transaction
				
			//get it again in case they change the headword
			$gloss = EieolGloss::with('elements.head_word')->find($id);
			
			return Response::json(array(
					'success' => true,
					'message' => 'Gloss was successfully updated.',
					'gloss_id' => $gloss->id,
					'gloss_display' => '<br>' . $gloss->getDisplayGloss(),
					'author_comments' => $gloss->author_comments,
					'author_done' => $gloss->author_done,
					'admin_comments' => $gloss->admin_comments
			));
	
		}
	}
	
}