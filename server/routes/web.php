<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EieolGlossController;
use App\Http\Controllers\EieolGlossedTextController;
use App\Http\Controllers\EieolGlossedTextGlossController;
use App\Http\Controllers\EieolGrammarController;
use App\Http\Controllers\EieolHeadWordController;
use App\Http\Controllers\EieolLessonController;
use App\Http\Controllers\EieolSeriesController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\IssueCommentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\PublicBookController;
use App\Http\Controllers\PublicEieolController;
use App\Http\Controllers\PublicIELexController;
use App\Http\Controllers\PublicLexiconController;
use App\Http\Controllers\PublicPageController;

Route::get('robots.txt', function() {
    if (config('app.env') === 'production') {
        return response(
            "User-agent: *
Disallow: /eieol_printable/"
        )->header('Content-Type', 'text/plain');
    } else {
        return response(
            "User-agent: *
Disallow: /"
        )->header('Content-Type', 'text/plain');
    }

});

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
    Route::get('lexicon/{lex_slug}/switchlang/{lang}', 'switch_lang');
    Route::get('lexicon/{lex_slug}/etymon/{etymon_id}', 'etymon');
    Route::get('lexicon/{lex_slug}/field/{field_id}', 'field');
    Route::get('lexicon/{lex_slug}/word/{word_id}', 'word_home');
    Route::get('lexicon/{lex_slug}/language/protolanguage', 'protolanguage_home');
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

Route::group(array('prefix'=> 'admin', 'middleware' => 'auth'), function() {
    Route::resource('/issue', IssueController::class);
    Route::resource('/issue_comment', IssueCommentController::class);
});

Route::group(array('prefix'=> 'admin2', 'middleware' => 'auth'), function() {
    Route::get('/eieol_series', fn() => redirect('/admin'));
    Route::get('/', fn() => redirect('/admin'));

    Route::resource('issues', IssueController::class);

    Route::get('/eieol_series/{id}/edit', [EieolSeriesController::class, 'edit']);
    Route::put('/eieol_lesson/update_text/{id}', [EieolLessonController::class, 'update_text']);
    Route::put('/eieol_lesson/update_translation/{id}', [EieolLessonController::class, 'update_translation']);
    Route::resource('/eieol_lesson', EieolLessonController::class);
    Route::resource('/eieol_grammar', EieolGrammarController::class);
    Route::resource('/eieol_glossed_text_gloss', EieolGlossedTextGlossController::class);
    Route::post('/eieol_glossed_text_gloss/copy_gloss', [EieolGlossedTextGlossController::class, 'postCopyGloss']);
    Route::resource('/eieol_glossed_text', EieolGlossedTextController::class);
    Route::get('/eieol_gloss/filtered_list', [AdminController::class, 'gloss_typeahead']);
    Route::resource('/eieol_gloss', EieolGlossController::class);
    Route::get('/eieol_head_word/filtered_list', [AdminController::class, 'headword_typeahead']);
    Route::resource('/eieol_head_word', EieolHeadWordController::class);
    Route::get('/eieol_head_word_keyword/filtered_list', [AdminController::class, 'headword_keyword_typeahead']);
    Route::get('/part_of_speech/filtered_list', [AdminController::class, 'part_of_speech_typeahead']);
    Route::get('/eieol_analysis/filtered_list', [AdminController::class, 'analysis_typeahead']);

    Route::post('/related_languages/attach_language', [EieolSeriesController::class, 'attach_language']);
    Route::post('/related_languages/{series_id}/detach_language/{language_id}', [EieolSeriesController::class, 'detach_language']);

    Route::post('/files/upload', [FilesController::class, 'post_file']);
});

Auth::routes(['register' => false]);
