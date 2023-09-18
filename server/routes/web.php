<?php

use App\Http\Controllers\PublicBookController;
use App\Http\Controllers\PublicEieolController;
use App\Http\Controllers\PublicIELexController;
use App\Http\Controllers\PublicLexiconController;
use App\Http\Controllers\PublicPageController;

Route::controller(PublicPageController::class)->group(function() {
    Route::get('/', 'index');
    Route::get('index', 'index');
    Route::get('books', 'books');
    Route::get('guides/eieol_author', 'guide_ea');
    Route::get('guides/eieol_user', 'guide_eu');
    Route::get('guides/lex_user', 'guide_lu');
    Route::get('lex', 'lex');
});

Route::controller(PublicBookController::class)->group(function() {
    Route::get('books/{book_slug}', 'bookHome');
    Route::get('books/{book_slug}/{section_slug}', 'bookSection');
});

Route::controller(PublicEieolController::class)->group(function() {
    Route::get('eieol', 'eieol');
    Route::get('eieol_lesson/{series_id}', 'eieol_lesson_redirect');
    Route::get('eieol/{series_name}', 'eieol_first_lesson');
    Route::get('eieol/{series_name}/{lesson_order}', 'eieol_lesson');
    Route::get('eieol_printable/{series_id}', 'eieol_printable');
    Route::get('eieol_toc/{series_id}', 'eieol_toc');
    Route::get('eieol_master_gloss/{series_id}/{language_id}', 'eieol_master_gloss');
    Route::get('eieol_base_form_dictionary/{series_id}/{language_id}', 'eieol_base_form_dictionary');
    Route::get('eieol_english_meaning_index/{series_id}/{language_id}', 'eieol_english_meaning_index');
});

Route::controller(PublicLexiconController::class)->group(function() {
    Route::get('lexicon/{lex_slug}', 'index');
    Route::get('lexicon/{lex_slug}/etymon/{etymon_id}', 'etymon');
    Route::get('lexicon/{lex_slug}/field/{field_id}', 'field');
    Route::get('lexicon/{lex_slug}/word/{word_id}', 'word_home');
    Route::get('lexicon/{lex_slug}/language/{lang_id}', 'lang_home');
    Route::get('lexicon/{lex_slug}/page/{page_slug_fragment}', 'page');
    Route::get('lexicon/{lex_slug}/data', 'data');
});

Route::get('lex_pokorny', function() {return redirect('lex/master', 301);});
Route::get('lex_semantic_field/{field_id}', array('as' => 'field_redirect', 'uses' =>'PublicIELexController@lex_semantic_field_redirect'));
Route::get('lex_reflex/{etyma_id}', function() {return redirect('lex/languages/', 301);});
Route::get('lex_semantic', function() {return redirect('lex/semantic/', 301);});
Route::get('lex_lang_reflexes/{language_id}', array('as' => 'reflexes_redirect', 'uses' =>'PublicIELexController@lex_lang_reflexes_redirect'));
Route::get('lex_language', function() {return redirect('lex/languages/', 301);});

Route::controller(PublicIELexController::class)->group(function() {
    Route::get('lex/master', 'lex_pokorny');
    Route::get('lex/master/{pokorny_number}', 'lex_reflex');
    Route::get('lex/languages', 'lex_language');
    Route::get('lex/languages/{language_abbr}', 'lex_lang_reflexes');
    Route::get('lex/semantic', 'lex_semantic');
    Route::get('lex/semantic/category/{cat_abbr}', 'lex_semantic_category');
    Route::get('lex/semantic/field/{field_abbr}', 'lex_semantic_field');
});

Route::get('/admin', 'AdminController@index');

Route::group(array('prefix'=> 'admin/api/v1', 'middleware' => 'auth'), function() {
    Route::resource('/issue', 'IssueController');
    Route::resource('/issue_comment', 'IssueCommentController');
});

Route::group(array('prefix'=> 'admin2', 'middleware' => 'auth'), function() {
    Route::get('admin_error', function() {
        return View::make('admin_error');
    });

    Route::get('admin_app', 'AdminController@app');
    Route::resource('issues', 'IssueController');

    Route::get('/eieol_series', 'EieolSeriesController@index');
    Route::get('/eieol_series/{id}/edit', 'EieolSeriesController@edit');
    Route::put('/eieol_lesson/update_translation/{id}', 'EieolLessonController@update_translation');
    Route::resource('/eieol_lesson', 'EieolLessonController');
    Route::resource('/eieol_grammar', 'EieolGrammarController');
    Route::resource('/eieol_glossed_text_gloss', 'EieolGlossedTextGlossController');
    Route::post('/eieol_glossed_text_gloss/copy_gloss', 'EieolGlossedTextGlossController@postCopyGloss');
    Route::resource('/eieol_glossed_text', 'EieolGlossedTextController');
    Route::get('/eieol_gloss/filtered_list', 'AdminController@gloss_typeahead');
    Route::resource('/eieol_gloss', 'EieolGlossController');
    Route::get('/eieol_head_word/filtered_list', 'AdminController@headword_typeahead');
    Route::resource('/eieol_head_word', 'EieolHeadWordController');
    Route::get('/eieol_head_word_keyword/filtered_list', 'AdminController@headword_keyword_typeahead');
    Route::get('/part_of_speech/filtered_list', 'AdminController@part_of_speech_typeahead');
    Route::get('/eieol_analysis/filtered_list', 'AdminController@analysis_typeahead');

    Route::post('/related_languages/attach_language', 'EieolSeriesController@attach_language');
    Route::post('/related_languages/{series_id}/detach_language/{language_id}', 'EieolSeriesController@detach_language');

    Route::post('/files/upload', 'FilesController@post_file');
});

Auth::routes(['register' => false]);

Route::get('/home', 'AdminController@index')->name('home');
