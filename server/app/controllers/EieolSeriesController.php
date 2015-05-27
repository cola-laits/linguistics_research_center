<?php

class EieolSeriesController extends BaseController {	
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$serieses = EieolSeries::all()->sortBy('order');
        return View::make('eieol_series.eieol_series_index', ['serieses' => $serieses]);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('eieol_series.eieol_series_form', ['action' => 'Create']);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
		$rules = array(
				'published'  => 'boolean',
				'order' => 'required|integer',
				'title' => 'required|unique:eieol_series',
				'menu_name'  => 'required',
				'menu_order'  => 'required|integer',
				'expanded_title'  => 'required',
				'use_old_gloss_ui'  => 'boolean',
		);
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			return Redirect::to('/admin2/eieol_series/create')
			->withErrors($validator->messages())
			->withInput();
		} else {
		
			$series = new EieolSeries;
			
			$series->published = Input::get('published');
			$series->order = Input::get('order');
			$series->title = Input::get('title');
			$series->menu_name = Input::get('menu_name');
			$series->menu_order = Input::get('menu_order');
			$series->expanded_title = Input::get('expanded_title');
			$series->use_old_gloss_ui = Input::get('use_old_gloss_ui');
			$series->created_by = Auth::user()->username;
			$series->updated_by = Auth::user()->username;
			
			$series->save();
			Session::flash('message', $series->title . ' has been created');
			return Redirect::to('/admin2/eieol_series/' . $series->id . '/edit');
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
		$series = EieolSeries::find($id);
		$lessons = EieolLesson::where('series_id', '=', $id)->get()->sortBy('order');
		return View::make('eieol_series.eieol_series_form', [ 'series' => $series, 'lessons' => $lessons, 'action' => 'Edit' ]);
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
				'order' => 'required|integer',
				'title' => 'required|unique:eieol_series,title,' . $id,
				'published'  => 'boolean',
				'menu_name'  => 'required',
				'menu_order'  => 'required|integer',
				'expanded_title'  => 'required',
				'use_old_gloss_ui'  => 'boolean',
		);
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			return Redirect::to('/admin2/eieol_series/' . $id . '/edit')
			->withErrors($validator->messages())
			->withInput();
		} else {
			$series = EieolSeries::find($id);
			
			$series->title = Input::get('title');
			$series->order = Input::get('order');
			$series->published = Input::get('published');
			$series->menu_name = Input::get('menu_name');
			$series->menu_order = Input::get('menu_order');
			$series->expanded_title = Input::get('expanded_title');
			$series->use_old_gloss_ui = Input::get('use_old_gloss_ui');
			$series->updated_by = Auth::user()->username;
				
			$series->save();
			Session::flash('message', $series->title . ' has been updated');
			return Redirect::to('/admin2/eieol_series/' . $id . '/edit');
		}
	}

}
