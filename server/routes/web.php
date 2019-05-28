<?php

Route::get('/', 'PublicPageController@index');
Route::get('index', 'PublicPageController@index');

Route::get('guides/eieol_author', 'PublicPageController@guide_ea');
Route::get('guides/eieol_user', 'PublicPageController@guide_eu');
Route::get('guides/lex_user', 'PublicPageController@guide_lu');

Route::get('eieol', 'PublicEieolController@eieol');

Route::get('eieol_lesson/{series_id}', 'PublicEieolController@eieol_lesson_redirect');
Route::get('eieol/{series_name}', 'PublicEieolController@eieol_first_lesson');
Route::get('eieol/{series_name}/{lesson_order}', 'PublicEieolController@eieol_lesson');

Route::get('eieol_printable/{series_id}', 'PublicEieolController@eieol_printable');
Route::get('eieol_toc/{series_id}', 'PublicEieolController@eieol_toc');
Route::get('eieol_master_gloss/{series_id}/{language_id}', 'PublicEieolController@eieol_master_gloss');
Route::get('eieol_base_form_dictionary/{series_id}/{language_id}', 'PublicEieolController@eieol_base_form_dictionary');
Route::get('eieol_english_meaning_index/{series_id}/{language_id}', 'PublicEieolController@eieol_english_meaning_index');

Route::get('eieol_text_list', 'PublicEieolController@eieol_text_list');
Route::get('eieol_text_toc/{series_id}', 'PublicEieolController@eieol_text_toc');
Route::get('eieol_text/{series_id}', 'PublicEieolController@eieol_text');

Route::get('lex', 'PublicLexController@lex');

Route::get('lex_pokorny', function() {return redirect('lex/master', 301);});
Route::get('lex_semantic_field/{field_id}', array('as' => 'field_redirect', 'uses' =>'PublicLexController@lex_semantic_field_redirect'));
Route::get('lex_reflex/{etyma_id}', function() {return redirect('lex/languages/', 301);});
Route::get('lex_semantic', function() {return redirect('lex/semantic/', 301);});
Route::get('lex_lang_reflexes/{language_id}', array('as' => 'reflexes_redirect', 'uses' =>'PublicLexController@lex_lang_reflexes_redirect'));
Route::get('lex_language', function() {return redirect('lex/languages/', 301);});

Route::get('lex/master', 'PublicLexController@lex_pokorny');
Route::get('lex/master/{pokorny_number}', 'PublicLexController@lex_reflex');
Route::get('lex/languages', 'PublicLexController@lex_language');
Route::get('lex/languages/{language_abbr}', 'PublicLexController@lex_lang_reflexes');
Route::get('lex/semantic', 'PublicLexController@lex_semantic');
Route::get('lex/semantic/category/{cat_abbr}', 'PublicLexController@lex_semantic_category');
Route::get('lex/semantic/field/{field_abbr}', 'PublicLexController@lex_semantic_field');

Route::get('/admin', 'AdminController@index');

Route::group(array('prefix'=> 'admin2', 'middleware' => 'auth'), function() {
    Route::get('admin_error', function() {
        return View::make('admin_error');
    });

    Route::resource('/eieol_series', 'EieolSeriesController');
    Route::put('/eieol_lesson/update_translation/{id}', 'EieolLessonController@update_translation');
    Route::resource('/eieol_lesson', 'EieolLessonController');
    Route::resource('/eieol_grammar', 'EieolGrammarController');
    Route::resource('/eieol_glossed_text_gloss', 'EieolGlossedTextGlossController');
    Route::post('/eieol_glossed_text_gloss/copy_gloss', 'EieolGlossedTextGlossController@postCopyGloss');
    Route::resource('/eieol_glossed_text', 'EieolGlossedTextController');
    Route::get('/eieol_gloss/filtered_list', 'EieolGlossController@filtered_list');
    Route::resource('/eieol_gloss', 'EieolGlossController');
    Route::get('/eieol_head_word/filtered_list', 'EieolHeadWordController@filtered_list');
    Route::resource('/eieol_head_word', 'EieolHeadWordController');
    Route::get('/eieol_head_word_keyword/filtered_list', 'EieolHeadWordKeywordController@filtered_list');
    Route::get('/part_of_speech/filtered_list', 'EieolPartOfSpeechController@filtered_list');
    Route::get('/eieol_analysis/filtered_list', 'EieolAnalysisController@filtered_list');
    Route::resource('/eieol_language', 'EieolLanguageController');

    Route::get('/related_languages/all_languages', 'EieolSeriesController@all_languages');
    Route::get('/related_languages/attached_languages/{series_id}', 'EieolSeriesController@attached_languages');
    Route::post('/related_languages/attach_language', 'EieolSeriesController@attach_language');
    Route::post('/related_languages/{series_id}/detach_language/{language_id}', 'EieolSeriesController@detach_language');

    Route::post('/files/upload', 'FilesController@post_file');
});

Route::group(array('prefix'=> 'admin2', 'before' => 'auth|admin'), function() {
    Route::get('/user/password_form/{id}', 'UserController@password_form');
    Route::put('/user/change_password/{id}', 'UserController@change_password');
    Route::resource('/user', 'UserController');
    Route::resource('/page', 'PageController');

    Route::get('/lexicon', 'AdminLexiconController@getIndex');
    Route::get('/lexicon/api/etyma', 'AdminLexiconController@getEtymas');
    Route::get('/lexicon/api/reflex', 'AdminLexiconController@getReflexes');
    Route::get('/lexicon/api/reflex_entry', 'AdminLexiconController@getReflexEntries');
    Route::get('/lexicon/api/reflex_pos', 'AdminLexiconController@getReflexPOSes');
    Route::get('/lexicon/api/sem_cat', 'AdminLexiconController@getSemCats');
    Route::get('/lexicon/api/sem_field', 'AdminLexiconController@getSemFields');
    Route::get('/lexicon/api/lang_fam', 'AdminLexiconController@getLangFams');
    Route::get('/lexicon/api/lang_subfam', 'AdminLexiconController@getLangSubfams');
    Route::get('/lexicon/api/lang', 'AdminLexiconController@getLangs');
    Route::get('/lexicon/api/source', 'AdminLexiconController@getSources');
    Route::get('/lexicon/api/pos', 'AdminLexiconController@getPOSes');

    Route::get('/lexicon/api/action/get', 'AdminLexiconController@getItem');
    Route::post('/lexicon/api/action/edit', 'AdminLexiconController@postEditItem');
    Route::post('/lexicon/api/action/delete', 'AdminLexiconController@postDeleteItem');
});

Auth::routes();

Route::get('/home', 'AdminController@index')->name('home');
