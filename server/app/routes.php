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

Route::get('/', function()
{
	return Redirect::to(Config::get('lrc_settings.static_site'));
});

Route::get('eieol', function()
{
	$data = array();
	$serieses = EieolSeries::all();
	$data['serieses'] = $serieses;
	return View::make('eieol')->with($data);
});