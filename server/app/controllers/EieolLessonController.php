<?php

class EieolLessonController extends BaseController {	


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$series = EieolSeries::find(Input::get('series_id'));
		$languages = array();
		$languages[''] = 'Select a Language';
		$languages += EieolLanguage::lists('language','id');
		return View::make('eieol_lesson.eieol_lesson_create', ['series' => $series,
															   'languages' => $languages]);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
		$rules = array(
				'order' => 'required|integer|unique:eieol_lesson,order,null,id,series_id,'. Input::get('series_id'),
				'title' => 'required|unique:eieol_lesson,title,null,id,series_id,'. Input::get('series_id'),
				'language' => 'required',
				'intro_text' => 'required',
				'series_id' => 'required|exists:eieol_series,id'
		);
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			return Redirect::to('/admin2/eieol_lesson/create?series_id=' . Input::get('series_id'))
			->withErrors($validator->messages())
			->withInput();
		} else {
		
			$lesson = new EieolLesson;
			
			$lesson->title = Normalizer::normalize(Input::get('title'), Normalizer::FORM_D );
			$lesson->order = Input::get('order');
			$lesson->series_id = Input::get('series_id');
			$lesson->language_id = Input::get('language');
			$lesson->intro_text = Normalizer::normalize(Input::get('intro_text'), Normalizer::FORM_D );
			$lesson->created_by = Auth::user()->username;
			$lesson->updated_by = Auth::user()->username;
			
			$lesson->save();
			Session::flash('message', $lesson->title . ' has been created');
			return Redirect::to('/admin2/eieol_lesson/' . $lesson->id . '/edit');
		}

	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$lesson = EieolLesson::with('series', 'language')->find($id);
		$grammars = EieolGrammar::where('lesson_id', '=', $id)->get()->sortBy('order');
		$glossed_texts = EieolGlossedText::with('glosses.language','glosses.elements.head_word.language')->where('lesson_id', '=', $id)->get()->sortBy('order');
		
		//get languages for pulldown
		$languages = array();
		$languages[''] = 'Select a Language';
		$languages += EieolLanguage::lists('language','id');
		
		//get etymas for pulldown
		$etymas = array();
		$etymas[0] = 'Select an Etymon';
		$etymas += LexEtyma::lists('entry', 'id');
		
		return View::make('eieol_lesson.eieol_lesson_edit', ['lesson' => $lesson, 
															 'grammars' => $grammars, 
															 'glossed_texts' => $glossed_texts,
															 'languages' => $languages,
															 'etymas' => $etymas]);
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
				'order' => 'required|integer|unique:eieol_lesson,order,' . $id . ',id,series_id,'. Input::get('series_id'),
				'title' => 'required|unique:eieol_lesson,title,' . $id . ',id,series_id,'. Input::get('series_id'),
				'language' => 'required',
				'intro_text' => 'required',
				'series_id' => 'required|exists:eieol_series,id'
		);
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
			));
		} else {
			$language_updated = DB::transaction(function($id) use ($id) {
	 			$lesson = EieolLesson::find($id);
	 			
	 			$language_updated = false;
	 			//if they change the language, we have to sweep all the glosses, head words and keywords
	 			if ($lesson->language_id != Input::get('language')) {
	 				$language_updated = true;
	 				$glossed_texts = EieolGlossedText::with('glosses.elements.head_word.keywords')->where('lesson_id', '=', $id)->get();
	 				foreach ($glossed_texts as $glossed_text) {
	 					foreach ($glossed_text->glosses as $gloss) {
	 						$gloss->language_id = Input::get('language');
	 						$gloss->save();
	 						foreach($gloss->elements as $element) {
	 							$element->head_word->language_id = Input::get('language');
	 							$element->head_word->save();
	 							foreach ($element->head_word->keywords as $keyword) {
	 								$keyword->language_id = Input::get('language');
	 								$keyword->save();
	 							}
	 						}
	 					}
	 				}
	 			}
				
	 			$lesson->title = Normalizer::normalize(Input::get('title'), Normalizer::FORM_D );
	 			$lesson->order = Input::get('order');
				$lesson->intro_text = Normalizer::normalize(Input::get('intro_text'), Normalizer::FORM_D );
				$lesson->language_id = Input::get('language');
				$lesson->author_comments = Normalizer::normalize(Input::get('author_comments'), Normalizer::FORM_D );
				$lesson->author_done = Input::get('author_done');
				$lesson->admin_comments = Normalizer::normalize(Input::get('admin_comments'), Normalizer::FORM_D );
	 			$lesson->updated_by = Auth::user()->username;
	 			$lesson->save();
	 			
	 			return $language_updated;
			});
			
			if ($language_updated) {
				return Response::json(array(
						'success' => true,
						'message' => 'Update was successful',
						'language_id' => Input::get('language'),
				));
			} else {
				return Response::json(array(
						'success' => true,
						'message' => 'Update was successful',
				));
			}
		}
	}
	
	
	public function update_translation($id)
	{
		$lesson = EieolLesson::find($id);
			
		$lesson->lesson_translation = Normalizer::normalize(Input::get('lesson_translation'), Normalizer::FORM_D );
		$lesson->translation_author_comments = Normalizer::normalize(Input::get('translation_author_comments'), Normalizer::FORM_D );
		$lesson->translation_author_done = Input::get('translation_author_done');
		$lesson->translation_admin_comments = Normalizer::normalize(Input::get('translation_admin_comments'), Normalizer::FORM_D );
		$lesson->updated_by = Auth::user()->username;
		$lesson->updated_by = Auth::user()->username;

		$lesson->save();
		//Session::flash('message', 'Translation has been updated');
		return Response::json(array(
				'success' => true,
				'message' => 'Translation was udated successfully'
		));
	}
	
	public function all_languages()
	{
		$return_languages = array();
		$languages = IsoLanguage::whereIn('Language_Type', array('E','A','H','C'))->orWhere('Part1', '!=', '')->orWhere('Part2B', '!=', '')->orWhere('Part2T', '!=', '')->get()->sortBy('Ref_Name');
		foreach($languages as $language) {
			$temp_dict = array();
			$temp_dict['text'] = $language->Ref_Name;
			$temp_dict['value'] = $language->id;
			if (substr($temp_dict['text'],0,1) != "/" && substr($temp_dict['text'],0,1) != "#") {
			  $return_languages[] = $temp_dict;
			}
		} 
		return Response::json($return_languages);
	}

}
