<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    Route::middleware(['can:manage_lexicon'])->group(function() {
        Route::get('help_lex', function () {
            return view('admin/help_lex');
        });
        Route::crud('lex_etyma', 'Lex_etymaCrudController');
        Route::crud('lex_reflex', 'Lex_reflexCrudController');
        Route::crud('lex_semantic_category', 'Lex_semantic_categoryCrudController');
        Route::crud('lex_semantic_field', 'Lex_semantic_fieldCrudController');
        Route::crud('lex_language_family', 'Lex_language_familyCrudController');
        Route::crud('lex_language_sub_family', 'Lex_language_sub_familyCrudController');
        Route::crud('lex_language', 'Lex_languageCrudController');
        Route::crud('lex_source', 'Lex_sourceCrudController');
        Route::crud('lex_part_of_speech', 'Lex_part_of_speechCrudController');
    });

    Route::crud('lex-lexicon', 'LexLexiconCrudController');
}); // this should be the absolute last line of this file
