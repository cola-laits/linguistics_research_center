<?php

class EieolGlossController extends BaseController {	
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//this does NOT list all glosses.  It's is a search that returns glosses that start with the url parm "gloss"
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
		return EieolGloss::with('head_word')->find($id)->toJson();
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

		$validator = Validator::make(Input::all(), $rules);
	
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
		
		$validator = Validator::make(Input::all(), $rules);
	
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