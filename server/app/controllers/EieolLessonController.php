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
			return Redirect::to('/admin/eieol_lesson/create?series_id=' . Input::get('series_id'))
			->withErrors($validator->messages())
			->withInput();
		} else {
		
			$lesson = new EieolLesson;
			
			$lesson->title = Input::get('title');
			$lesson->order = Input::get('order');
			$lesson->series_id = Input::get('series_id');
			$lesson->language_id = Input::get('language');
			$lesson->intro_text = Input::get('intro_text');
			$lesson->created_by = Auth::user()->username;
			$lesson->updated_by = Auth::user()->username;
			
			$lesson->save();
			Session::flash('message', $lesson->title . ' has been created');
			return Redirect::to('/admin/eieol_lesson/' . $lesson->id . '/edit');
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
		$lesson = EieolLesson::find($id);
		$series = $lesson->series;
		$grammars = EieolGrammar::where('lesson_id', '=', $id)->get()->sortBy('order');
		$glossed_texts = EieolGlossedText::with('glosses.head_word')->where('lesson_id', '=', $id)->get()->sortBy('order');
		
		//get languages for pulldown
		$languages = array();
		$languages[''] = 'Select a Language';
		$languages += EieolLanguage::lists('language','id');
		
		return View::make('eieol_lesson.eieol_lesson_edit', ['lesson' => $lesson, 
															 'series' => $series, 
															 'grammars' => $grammars, 
															 'glossed_texts' => $glossed_texts,
															 'languages' => $languages]);
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
	 				$glossed_texts = EieolGlossedText::with('glosses.head_word.keywords')->where('lesson_id', '=', $id)->get();
	 				foreach ($glossed_texts as $glossed_text) {
	 					foreach ($glossed_text->glosses as $gloss) {
	 						$gloss->language_id = Input::get('language');
	 						$gloss->save();
	 						$gloss->head_word->language_id = Input::get('language');
	 						$gloss->head_word->save();
	 						foreach ($gloss->head_word->keywords as $keyword) {
	 							$keyword->language_id = Input::get('language');
	 							$keyword->save();
	 						}
	 					}
	 				}
	 			}
				
	 			$lesson->title = Input::get('title');
	 			$lesson->order = Input::get('order');
				$lesson->intro_text = Input::get('intro_text');
				$lesson->language_id = Input::get('language');
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
			
		$lesson->lesson_translation = Input::get('lesson_translation');
		$lesson->updated_by = Auth::user()->username;

		$lesson->save();
		//Session::flash('message', 'Translation has been updated');
		return Response::json(array(
				'success' => true,
				'message' => 'Translation was udated successfully'
		));
	}

}
