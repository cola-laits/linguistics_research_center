<?php

class EieolSeriesController extends BaseController {	
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
   		if (Auth::user()->isAdmin()) {
			$serieses = EieolSeries::all()->sortBy('order');
   		} else {
   			$auths = Auth::user()->seriesAuthorizations();
   			$serieses = EieolSeries::whereIn('id', $auths)->get()->sortBy('order');
   		}
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
            $series->meta_tags = Input::get('meta_tags');
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
            $series->meta_tags = Input::get('meta_tags');
			$series->updated_by = Auth::user()->username;
				
			$series->save();
			Session::flash('message', $series->title . ' has been updated');
			return Redirect::to('/admin2/eieol_series/' . $id . '/edit');
		}
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
	
	public function attached_languages($id)
	{
	  
	  $return_languages = array();
	  $series = EieolSeries::with('languages')->find($id);
	  $languages = $series->languages;
	  foreach($languages as $language) {
			$temp_dict = array();
			$temp_dict['text'] = $language->display;
			$temp_dict['value'] = $language->lang;
			$return_languages[] = $temp_dict;
		} 
		return Response::json($return_languages);
	  
	}

}
