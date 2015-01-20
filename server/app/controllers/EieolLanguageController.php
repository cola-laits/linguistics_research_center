<?php

class EieolLanguageController extends BaseController {	
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$languages = EieolLanguage::all();
        return View::make('eieol_language.eieol_language_index', ['languages' => $languages]);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('eieol_language.eieol_language_form', ['action' => 'Create']);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
		$rules = array(
				'language' => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			return Redirect::to('/admin/eieol_language/create')
			->withErrors($validator->messages())
			->withInput();
		} else {
		
			$language = new EieolLanguage;
			
			$language->language = Input::get('language');
			$language->custom_keyboard_layout = Input::get('custom_keyboard_layout');
			$language->custom_sort = Input::get('custom_sort');
			$language->created_by = Auth::user()->username;
			$language->updated_by = Auth::user()->username;
			
			$language->save();
			Session::flash('message', $language->language . ' has been created');
			return Redirect::to('/admin/eieol_language');
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
		$language = EieolLanguage::find($id);
		return View::make('eieol_language.eieol_language_form', [ 'language' => $language, 'action' => 'Edit' ]);
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
				'language' => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			return Redirect::to('/admin/eieol_language/' . $id . '/edit')
			->withErrors($validator->messages())
			->withInput();
		} else {
			$language = EieolLanguage::find($id);
			
			$language->language = Input::get('language');
			$language->custom_keyboard_layout = Input::get('custom_keyboard_layout');
			$language->custom_sort = Input::get('custom_sort');
			$language->updated_by = Auth::user()->username;
			
			$language->save();
			
			Session::flash('message', $language->language . ' has been updated');
			return Redirect::to('/admin/eieol_language');
		}
	}

}