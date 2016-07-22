<?php

Route::get('/', 'PublicController@index');
Route::get('index', 'PublicController@index');

Route::get('eieol', 'PublicController@eieol');
//Route::get('eieol_lesson/{series_id}', 'PublicController@eieol_lesson');

Route::get('eieol/{series_name}', 'PublicController@eieol_first_lesson_by_name');
Route::get('eieol/{series_name}/{lesson_order}', 'PublicController@eieol_lesson_by_name');

Route::get('eieol_printable/{series_id}', 'PublicController@eieol_printable');
Route::get('eieol_toc/{series_id}', 'PublicController@eieol_toc');
Route::get('eieol_master_gloss/{series_id}/{language_id}', 'PublicController@eieol_master_gloss');
Route::get('eieol_base_form_dictionary/{series_id}/{language_id}', 'PublicController@eieol_base_form_dictionary');
Route::get('eieol_english_meaning_index/{series_id}/{language_id}', 'PublicController@eieol_english_meaning_index');

Route::get('eieol_text_list', 'PublicController@eieol_text_list');
Route::get('eieol_text_toc/{series_id}', 'PublicController@eieol_text_toc');
Route::get('eieol_text/{series_id}', 'PublicController@eieol_text');

Route::get('lex', 'PublicController@lex');

Route::get('lex_pokorny', 'PublicController@lex_pokorny');
Route::get('lex/master', 'PublicController@lex_pokorny');

Route::get('lex_reflex/{etyma_id}', 'PublicController@lex_reflex');
Route::get('lex/master/{etyma_id}', 'PublicController@lex_reflex_by_pokorny_number');

Route::get('lex_language', 'PublicController@lex_language');
Route::get('lex/languages', 'PublicController@lex_language');

Route::get('lex_lang_reflexes/{language_id}', 'PublicController@lex_lang_reflexes');
Route::get('lex/languages/{language_id}', 'PublicController@lex_lang_reflexes');

Route::get('lex_semantic', 'PublicController@lex_semantic');
Route::get('lex/semantic', 'PublicController@lex_semantic');

Route::get('lex_semantic_category/{cat_id}', 'PublicController@lex_semantic_category');
Route::get('lex/semantic/category/{cat_id}', 'PublicController@lex_semantic_category');

Route::get('lex_semantic_field/{field_id}', 'PublicController@lex_semantic_field');
Route::get('lex/semantic/field/{field_id}', 'PublicController@lex_semantic_field');

//Route::get('lex/{etyma_id}', 'PublicController@lex_reflex_by_pokorny_number');

Route::get('rest/eieol_serieses', 'PublicController@rest_eieol_serieses');
Route::get('rest/eieol_series/{series_id}', 'PublicController@rest_eieol_series');
Route::get('rest/eieol_lesson/{lesson_id}', 'PublicController@rest_eieol_lesson');

Route::get('login', 'LoginController@login_page');
Route::post('login', 'LoginController@login_action');
Route::get('logout', 'LoginController@logout');

Route::group(array('prefix'=> 'admin2', 'before' => 'auth'), function() {
	Route::get('admin_error', function() {
	    return View::make('admin_error');
	});
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
	Route::get('/part_of_speech/filtered_list', 'EieolPartOfSpeechController@filtered_list');
	Route::get('/eieol_analysis/filtered_list', 'EieolAnalysisController@filtered_list');
	Route::resource('/eieol_language', 'EieolLanguageController');

});	

Route::group(array('prefix'=> 'admin2', 'before' => 'auth|admin'), function() {
	Route::get('/user/password_form/{id}', 'UserController@password_form');
	Route::put('/user/change_password/{id}', 'UserController@change_password');
	Route::resource('/user', 'UserController');
});