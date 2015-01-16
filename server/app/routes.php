<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

function get_series_info($series_id) {
	$data = array();
	$data['series'] = EieolSeries::find($series_id);
	$data['lessons'] = EieolLesson::with('grammars', 'language')->where('series_id', '=', $series_id)->get()->sortBy('order');
	
	$data['languages'] = array();
	foreach($data['lessons'] as $lesson) {
		if (!in_array($lesson->language, $data['languages'])) {
			$data['languages'][] = $lesson->language;
		}
	}
	
	return $data;
}

//share lets us pass data to all routes
View::share('static_site',Config::get('lrc_settings.static_site'));
View::share('lesson_menu', EieolSeries::where('published', '=', True)->get()->sortBy('menu_order'));

Route::get('/', function()
{
	return Redirect::to(Config::get('lrc_settings.static_site'));
});

Route::get('eieol', function()
{
	$data = array();
	$data['serieses'] = EieolSeries::where('published', '=', True)->get()->sortBy('order');
	return View::make('eieol')->with($data);
});

Route::get('eieol_lesson/{series_id}', function($series_id)
{
	$data = get_series_info($series_id);
	
	if (Input::has('id')) {
		$data['lesson'] = EieolLesson::with('grammars')
									->with('glossed_texts.glosses.elements.head_word')
									->where('id', '=', Input::get('id'))
									->firstOrFail();
	} else {
		//if they didn't send an id, get the first lesson
		$data['lesson'] = EieolLesson::with('grammars')
									 ->with('glossed_texts.glosses.elements.head_word')
									 ->where('series_id', '=', $series_id)
									 ->orderBy('order')
									 ->first();
	}
	$data['lesson_text'] = '';
	foreach ($data['lesson']->glossed_texts as $glossed_text) {
		$data['lesson_text'] .= $glossed_text->glossed_text . ' ';
	}
	
	return View::make('eieol_lesson')->with($data);
});

Route::get('eieol_toc/{series_id}', function($series_id)
{
	$data = get_series_info($series_id);
	return View::make('eieol_toc')->with($data);
});

Route::get('eieol_master_gloss/{series_id}/{language_id}', function($series_id, $language_id)
{
	$data = get_series_info($series_id);
	$data['language'] = EieolLanguage::find($language_id);
	$lessons = EieolLesson::with('glossed_texts.glosses.elements.head_word')
							->where('series_id', '=', $series_id)
							->where('language_id', '=', $language_id)
							->get()
							->sortBy('order');
	$data['glosses'] = array();
	foreach ($lessons as $lesson) {
		foreach ($lesson->glossed_texts as $glossed_text) {
			foreach ($glossed_text->glosses as $gloss) {
				$key = $gloss->surface_form;
				$i = 0;
				foreach($gloss->elements as $element){
					$i++;
					if ($i != 1) {
						$key .= ' + ';
					}
					$key .= $element->part_of_speech . '; ' .
							$element->analysis . ' ';
				}
				if (!key_exists($key, $data['glosses'])) {
					$data['glosses'][$key] = $gloss->toArray();
					$data['glosses'][$key]['displayGlossForMasterGloss'] = $gloss->getDisplayGlossForMasterGloss();
					$data['glosses'][$key]['glossed_text_gloss_ids'] = array();
					$data['glosses'][$key]['glossed_text_gloss_ids'][$gloss->pivot->id] = $lesson;
				} else {	
					$data['glosses'][$key]['glossed_text_gloss_ids'][$gloss->pivot->id] = $lesson;
				}
			}
		}
	}
	ksort($data['glosses']);
	return View::make('eieol_master_gloss')->with($data);
});

Route::get('eieol_base_form_dictionary/{series_id}/{language_id}', function($series_id, $language_id)
{
	$data = get_series_info($series_id);
	$data['language'] = EieolLanguage::find($language_id);
	$lessons = EieolLesson::with('glossed_texts.glosses.elements.head_word')
		->where('series_id', '=', $series_id)
		->where('language_id', '=', $language_id)
		->get()
		->sortBy('order');
	$data['head_words'] = array();
	foreach ($lessons as $lesson) {
		foreach ($lesson->glossed_texts as $glossed_text) {
			foreach ($glossed_text->glosses as $gloss) {
				foreach ($gloss->elements as $element) {
					$key = $element->head_word->word . ' -- ' . $element->head_word->definition;
					if (!key_exists($key, $data['head_words'])) {
						$data['head_words'][$key] = $element->head_word->toArray();
						$data['head_words'][$key]['word'] = htmlentities($data['head_words'][$key]['word']);
						$data['head_words'][$key]['glossed_text_gloss_ids'] = array();
						$data['head_words'][$key]['glossed_text_gloss_ids'][$gloss->pivot->id] = $lesson;
					} else {
						$data['head_words'][$key]['glossed_text_gloss_ids'][$gloss->pivot->id] = $lesson;
					}
			}
			}
		}
	}
	ksort($data['head_words']);
	return View::make('eieol_base_form_dictionary')->with($data);
});


Route::get('eieol_english_meaning_index/{series_id}/{language_id}', function($series_id, $language_id)
{
	$data = get_series_info($series_id);
	$data['language'] = EieolLanguage::find($language_id);
	return View::make('eieol_english_meaning_index')->with($data);
});

Route::get('login', function()
{
	return View::make('login');
});

Route::post('login', function()
{
	$username = Input::get('username');
	$password = Input::get('password');
	
	if (Auth::attempt(['username' => $username, 'password' => $password]))
	{
		return Redirect::intended('admin/eieol_series');
	}
	
	return Redirect::back()
	->withInput()
	->withErrors('That username/password combo does not exist.');
});

Route::get('logout', function()
{
	Auth::logout();
	Session::flush();
 	return Redirect::to('/');
});


Route::group(array('prefix'=> 'admin', 'before' => 'auth'), function() {
	Route::resource('/user', 'UserController');
	Route::resource('/eieol_series', 'EieolSeriesController');
	Route::put('/eieol_lesson/update_translation/{id}', 'EieolLessonController@update_translation');
	Route::resource('/eieol_lesson', 'EieolLessonController');
	Route::resource('/eieol_grammar', 'EieolGrammarController');
	Route::resource('/eieol_glossed_text_gloss', 'EieolGlossedTextGlossController');
	Route::resource('/eieol_glossed_text', 'EieolGlossedTextController');
	Route::get('/eieol_gloss/filtered_list', 'EieolGlossController@filtered_list');
	Route::resource('/eieol_gloss', 'EieolGlossController');
	Route::get('/eieol_head_word/filtered_list', 'EieolHeadWordController@filtered_list');
	Route::resource('/eieol_head_word', 'EieolHeadWordController');
	Route::get('/eieol_head_word_keyword/filtered_list', 'EieolHeadWordKeywordController@filtered_list');
	Route::get('/part_of_speech/filtered_list', 'PartOfSpeechController@filtered_list');
	Route::get('/eieol_analysis/filtered_list', 'EieolAnalysisController@filtered_list');
	Route::resource('/eieol_language', 'EieolLanguageController');
});