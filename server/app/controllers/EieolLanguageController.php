<?php

Validator::extend('valid_custom_keyboard_layout', function($field,$value,$parameters){
	$chars = explode(',',$value); //must be comma separated
	foreach($chars as $char) {
		$char = trim($char); //don't care about whitespace
		//must start and end with quotes
		if (strpos($char,"'") === false ){
			return false;
		}
		if (strpos($char,"'") != 0 ){
			return false;
		}
		if (strrpos($char,"'") != (strlen($char)-1) ){
			return false;
		}
	}
	return true;
});


class EieolLanguageController extends BaseController {	
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$languages = EieolLanguage::all()->sortBy('language');
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
				'lang_attribute' => 'required',
				'class_attribute' => 'required',
				'custom_keyboard_layout' => 'valid_custom_keyboard_layout',
		);
		$messages = array(
				'custom_keyboard_layout.valid_custom_keyboard_layout'=>'The keyboard layout must be a comma separated list with each entry quoted.'
		);
		
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			return Redirect::to('/admin2/eieol_language/create')
			->withErrors($validator->messages())
			->withInput();
		} else {
		
			$language = new EieolLanguage;
			
			$language->language = Input::get('language');
			$language->custom_keyboard_layout = Normalizer::normalize(Input::get('custom_keyboard_layout'), Normalizer::FORM_C );
			$language->custom_sort = Normalizer::normalize(Input::get('custom_sort'), Normalizer::FORM_C );
			$language->lang_attribute = Input::get('lang_attribute');
			$language->class_attribute = Input::get('class_attribute');
			$language->created_by = Auth::user()->username;
			$language->updated_by = Auth::user()->username;
			
			$language->save();
			Session::flash('message', $language->language . ' has been created');
			return Redirect::to('/admin2/eieol_language/' . $language->id . '/edit');
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
				'lang_attribute' => 'required',
				'class_attribute' => 'required',
				'custom_keyboard_layout' => 'valid_custom_keyboard_layout',
		);
		$messages = array(
				'custom_keyboard_layout.valid_custom_keyboard_layout'=>'The keyboard layout must be a comma separated list with each entry quoted.'
		);
		
		$validator = Validator::make(Input::all(), $rules,$messages);
		
		if ($validator->fails()) {
			return Redirect::to('/admin2/eieol_language/' . $id . '/edit')
			->withErrors($validator->messages())
			->withInput();
		} else {
			$language = EieolLanguage::find($id);
			
			$language->language = Input::get('language');
			$language->custom_keyboard_layout = Normalizer::normalize(Input::get('custom_keyboard_layout'), Normalizer::FORM_C );
			$language->custom_sort = Normalizer::normalize(Input::get('custom_sort'), Normalizer::FORM_C );
			$language->lang_attribute = Input::get('lang_attribute');
			$language->class_attribute = Input::get('class_attribute');
			$language->updated_by = Auth::user()->username;
			
			$language->save();
			
			Session::flash('message', $language->language . ' has been updated');
			return Redirect::to('/admin2/eieol_language/' . $language->id . '/edit');
		}
	}

}