<?php

Route::get('/', 'PublicController@index');
Route::get('eieol', 'PublicController@eieol');
Route::get('eieol_lesson/{series_id}', 'PublicController@eieol_lesson');
Route::get('eieol_toc/{series_id}', 'PublicController@eieol_toc');
Route::get('eieol_master_gloss/{series_id}/{language_id}', 'PublicController@eieol_master_gloss');
Route::get('eieol_base_form_dictionary/{series_id}/{language_id}', 'PublicController@eieol_base_form_dictionary');
Route::get('eieol_english_meaning_index/{series_id}/{language_id}', 'PublicController@eieol_english_meaning_index');

Route::get('login', 'LoginController@login_page');
Route::post('login', 'LoginController@login_action');
Route::get('logout', 'LoginController@logout');

Route::group(array('prefix'=> 'admin', 'before' => 'auth'), function() {
	Route::get('/user/password_form/{id}', 'UserController@password_form');
	Route::put('/user/change_password/{id}', 'UserController@change_password');
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
	Route::get('/part_of_speech/filtered_list', 'EieolPartOfSpeechController@filtered_list');
	Route::get('/eieol_analysis/filtered_list', 'EieolAnalysisController@filtered_list');
	Route::resource('/eieol_language', 'EieolLanguageController');
	
	Route::get('load', 'LoadController@load');
	Route::post('eieol_delete', 'LoadController@eieol_delete');
	Route::post('eieol_load', 'LoadController@eieol_load');
	Route::post('index_load', 'LoadController@index_load');
	Route::post('element_count', 'LoadController@element_count');
	Route::post('pos_analysis_load', 'LoadController@pos_analysis_load');
	Route::post('lex_sources_load', 'LoadController@lex_sources_load');
	Route::post('lex_pos_load', 'LoadController@lex_pos_load');
	Route::post('lex_lang_load', 'LoadController@lex_lang_load');
	Route::post('lex_sem_load', 'LoadController@lex_sem_load');
});	