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
		if (Auth::user()->isAdmin()) {
			$languages = EieolLanguage::all()->sortBy('language');
		} else {
			$auths = Auth::user()->seriesAuthorizations();
			$languages = array();
			$serieses = EieolSeries::whereIn('id', $auths)->get()->sortBy('order');
			foreach ($serieses as $series) {
				foreach ($series->lessons as $lesson) {
					if (!in_array($lesson->language_id,$languages)) {
						$languages[] = $lesson->language_id;
					} //if
				} //for lessons
			} //for series
			$languages = EieolLanguage::whereIn('id', $languages)->get()->sortBy('order');
		} //if amdin
				
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
			$language->custom_keyboard_layout = Normalizer::normalize(Input::get('custom_keyboard_layout'), Normalizer::FORM_D );
			$language->custom_sort = Normalizer::normalize(Input::get('custom_sort'), Normalizer::FORM_D );
			$language->substitutions = Normalizer::normalize(Input::get('substitutions'), Normalizer::FORM_D );
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
		
		//build list of all chars used by this language
		$chars = array();
		
		//get all glosses and headwords
		$lessons = EieolLesson::with('glossed_texts.glosses.elements.head_word')
		->where('language_id', '=', $id)
		->select(array('id'))
		->get();
		
		//loop through all the lessons, glossed texts, glosses and headwords to build list of used chars
		foreach ($lessons as $lesson) {
			foreach ($lesson->glossed_texts as $glossed_text) {
				foreach ($glossed_text->glosses as $gloss) {
					
					//clean up surface_form
					$surface_form = strip_tags($gloss->surface_form); //remove any tags like sup or sub
					$surface_form = str_replace(' ', '', $surface_form); //replace any whitespace
					$surface_form = str_replace(',', '', $surface_form); //remove any commas
					//print '<xmp>' . $surface_form . '</xmp>';
					
					$hold_char = '';
					$len = mb_strlen($surface_form,'UTF-8') - 1;
					for ($i = $len; $i >=0;  $i-- ) { //loop through each code point backwards
						$code_point = mb_substr($surface_form, $i, 1,'UTF-8');
						//print '<xmp>' . $i . ' ' . $code_point . ' ' . preg_match('/\p{Mn}/u', $code_point) . '</xmp>';
						if (preg_match('/\p{Mn}/u', $code_point)) { //it's a combining mark, save it to add to preceding char 
							$hold_char = $code_point . $hold_char;
						} else { //regular char
							//print json_encode($hold_char) . '.';
							$hold_char = $code_point . $hold_char; //add it to whatever we had before
							if (!in_array($hold_char, $chars)) { // if we don't already have it, add it to array
								$chars[] = $hold_char;
							}
							$hold_char = ''; //reset to start over
						} //if combining mark
					} // loop through surface form's code points
					
					//loop through elements to get headwords
 					foreach ($gloss->elements as $element) {
 						//clean up headword
 						$word = $element->head_word->word;
 						$len = mb_strlen($word,'UTF-8') -1;
 						$word = mb_substr($word,1,$len-1,'UTF-8'); //remove first and last characters, '<' and '>'
 						$word = strip_tags($word); //remove any tags like sup or sub
 						$word = str_replace(' ', '', $word); //replace any whitespace
 						$word = str_replace(',', '', $word); //remove any commas
 						//print '<xmp>    ' . $word. '</xmp>';
 						
 						$hold_char = '';
 						$len = mb_strlen($word,'UTF-8') -1;
 						for ($i = $len; $i >=0;  $i-- ) { //loop through each code point backwards
 							$code_point = mb_substr($word, $i, 1,'UTF-8');
 							if (preg_match('/\p{Mn}/u', $code_point)) { //it's a combining mark, save it to add to preceding char
 								$hold_char = $code_point . $hold_char;
 							} else { //regular char
 								//print json_encode($hold_char) . '.';
 								$hold_char = $code_point . $hold_char; //add it to whatever we had before
 								if (!in_array($hold_char, $chars)) { // if we don't already have it, add it to array
 									$chars[] = $hold_char;
 								}
 								$hold_char = ''; //reset to start over
 							} //if combining mark							
 							
 						} //loop through word's code points
 					} //loop through elements
				} //loop through glosses
			} //loop through glossed texts
		} //loop through lessons
					
		asort($chars);
		return View::make('eieol_language.eieol_language_form', [ 'language' => $language, 'action' => 'Edit', 'chars' => $chars ]);
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
			$language->custom_keyboard_layout = Normalizer::normalize(Input::get('custom_keyboard_layout'), Normalizer::FORM_D );
			$language->custom_sort = Normalizer::normalize(Input::get('custom_sort'), Normalizer::FORM_D );
			$language->substitutions = Normalizer::normalize(Input::get('substitutions'), Normalizer::FORM_D );
			$language->lang_attribute = Input::get('lang_attribute');
			$language->class_attribute = Input::get('class_attribute');
			$language->updated_by = Auth::user()->username;
			
			$language->save();
			
			Session::flash('message', $language->language . ' has been updated');
			return Redirect::to('/admin2/eieol_language/' . $language->id . '/edit');
		}
	}

}