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
		return View::make('eieol_lesson.eieol_lesson_form', ['series' => $series, 'action' => 'Create']);
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
				'intro_text' => 'required',
				'series_id' => 'required'
				//'series_id' => 'required|exists:eieol_series,id'
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
			$lesson->intro_text = Input::get('intro_text');
			$lesson->lesson_translation = Input::get('lesson_translation');
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
		return View::make('eieol_lesson.eieol_lesson_form', ['lesson' => $lesson, 'series' => $series, 'action' => 'Edit' ]);
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
				'intro_text' => 'required',
				'series_id' => 'required'
				//'series_id' => 'required|exists:eieol_series,id'
		);
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			return Redirect::to('/admin/eieol_lesson/' . $id . '/edit')
			->withErrors($validator->messages())
			->withInput();
		} else {
			$lesson = EieolLesson::find($id);
			
			$lesson->title = Input::get('title');
			$lesson->order = Input::get('order');
			$lesson->series_id = Input::get('series_id');
			$lesson->intro_text = Input::get('intro_text');
			$lesson->lesson_translation = Input::get('lesson_translation');
			$lesson->updated_by = Auth::user()->username;
				
			$lesson->save();
			Session::flash('message', $lesson->title . ' has been updated');
			return Redirect::to('/admin/eieol_lesson/' . $id . '/edit');
		}
	}

}
