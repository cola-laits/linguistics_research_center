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

Route::get('lesson/{series_id}/{order}', function($series_id, $order)
{
	$data = array();
	$data['lesson'] = EieolLesson::with('grammars')
								 ->with('glossed_texts.glosses.head_word')
								 ->where('series_id', '=', $series_id)
								 ->where('order', '=', $order)
								 ->firstOrFail();
	
	$data['lesson_text'] = '';
	foreach ($data['lesson']->glossed_texts as $glossed_text) {
		$data['lesson_text'] .= $glossed_text->glossed_text . ' ';
	}
	
	return View::make('lesson')->with($data);
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
	Route::resource('/eieol_gloss', 'EieolGlossController');
	Route::resource('/eieol_head_word', 'EieolHeadWordController');
});