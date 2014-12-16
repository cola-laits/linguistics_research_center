<?php

class EieolGlossController extends BaseController {	
	
	public function filtered_list()
	{
		//this is a search that returns glosses that start with the url parm "gloss"
		$text = '';
		$glosses = EieolGloss::with('head_word')->where('surface_form', 'LIKE', Input::get('gloss') . '%')->take(25)->get()->sortBy('surface_form');
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
		$gloss = EieolGloss::with('head_word', 'glossed_texts.lesson')->find($id);
		$return_gloss = $gloss->toArray();
		
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
			'surface_form' => 'required|unique:eieol_gloss,surface_form,null,id,part_of_speech,' . Input::get('part_of_speech') . ',analysis,' . Input::get('analysis'), 
			'part_of_speech' => 'required',
			'contextual_gloss' => 'required',
			'head_word_id' => 'required|exists:eieol_head_word,id'
		);
		$messages = array(
				'surface_form.unique' => 'This Surface Form/Part of Speech/Analysis combination already exists',
		);

		$validator = Validator::make(Input::all(), $rules, $messages);
	
		if ($validator->fails()) {
			return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
			));
		} else {
			$gloss = new EieolGloss;
	
			$gloss->surface_form = Input::get('surface_form');
			$gloss->part_of_speech = Input::get('part_of_speech');
			$gloss->analysis = Input::get('analysis');
			$gloss->contextual_gloss = Input::get('contextual_gloss');
			$gloss->head_word_id = Input::get('head_word_id');
			$gloss->created_by = Auth::user()->username;
			$gloss->updated_by = Auth::user()->username;
	
			$gloss->save();
			
			//add part of speech if new
			if (PartOfSpeech::where('part_of_speech', '=', Input::get('part_of_speech'))->count() == 0 ) {
				$part_of_speech = new PartOfSpeech;
				$part_of_speech->part_of_speech = Input::get('part_of_speech');
				$part_of_speech->created_by = Auth::user()->username;
				$part_of_speech->updated_by = Auth::user()->username;
				$part_of_speech->save();
			}
				
			//add analysis if new
			if (Input::has('analysis')) {
				if (EieolAnalysis::where('analysis', '=', Input::get('analysis'))->count() == 0 ) {
					$analysis = new EieolAnalysis;
					$analysis->analysis = Input::get('analysis');
					$analysis->created_by = Auth::user()->username;
					$analysis->updated_by = Auth::user()->username;
					$analysis->save();
				}
			}
			
			//get it to return full display with head word
			$gloss = EieolGloss::with('head_word')->find($gloss->id);
			
			return Response::json(array(
					'success' => true,
					'added' => true,
					'gloss_id' => $gloss->id,
					'gloss_display' => $gloss->getDisplayGloss(),
					'message' => 'Gloss was successfully added.'
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
			'surface_form' => 'required|unique:eieol_gloss,surface_form,' . $id . ',id,part_of_speech,' . Input::get('part_of_speech') . ',analysis,' . Input::get('analysis'), 
			'part_of_speech' => 'required',
			'contextual_gloss' => 'required',
			'head_word_id' => 'required|exists:eieol_head_word,id'
		);
		$messages = array(
				'surface_form.unique' => 'This Surface Form/Part of Speech/Analysis combination already exists',
		);
		
		$validator = Validator::make(Input::all(), $rules,$messages);
	
		if ($validator->fails()) {
			return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
			));
		} else {
			$gloss = EieolGloss::with('head_word')->find($id);
			
			$gloss->surface_form = Input::get('surface_form');
			$gloss->part_of_speech = Input::get('part_of_speech');
			$gloss->analysis = Input::get('analysis');
			$gloss->contextual_gloss = Input::get('contextual_gloss');
			$gloss->head_word_id = Input::get('head_word_id');
			$gloss->updated_by = Auth::user()->username;
			
			$gloss->save();
			
			//add part of speech if new
			if (PartOfSpeech::where('part_of_speech', '=', Input::get('part_of_speech'))->count() == 0 ) {
				$part_of_speech = new PartOfSpeech;
				$part_of_speech->part_of_speech = Input::get('part_of_speech');
				$part_of_speech->created_by = Auth::user()->username;
				$part_of_speech->updated_by = Auth::user()->username;
				$part_of_speech->save();
			}
			
			//add analysis if new
			if (Input::has('analysis')) {
				if (EieolAnalysis::where('analysis', '=', Input::get('analysis'))->count() == 0 ) {
					$analysis = new EieolAnalysis;
					$analysis->analysis = Input::get('analysis');
					$analysis->created_by = Auth::user()->username;
					$analysis->updated_by = Auth::user()->username;
					$analysis->save();
				}
			}
				
			//get it again in case they change the headword
			$gloss = EieolGloss::with('head_word')->find($id);
			
			return Response::json(array(
					'success' => true,
					'message' => 'Gloss was successfully updated.',
					'gloss_id' => $gloss->id,
					'gloss_display' => '<br>' . $gloss->getDisplayGloss(),
			));
	
		}
	}
	
}