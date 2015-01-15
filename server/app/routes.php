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
	$data['lessons'] = EieolLesson::with('grammars')->where('series_id', '=', $series_id)->get()->sortBy('order');
	
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