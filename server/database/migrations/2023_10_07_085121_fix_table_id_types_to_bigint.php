<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // drop existing fks
        Schema::table('eieol_element', function (Blueprint $table) {
            $table->dropForeign(['gloss_id']);
            $table->dropForeign(['head_word_id']);
        });
        Schema::table('eieol_gloss', function (Blueprint $table) {
            $table->dropForeign(['glossed_text_id']);
            $table->dropForeign(['language_id']);
        });
        Schema::table('eieol_glossed_text', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
        });
        Schema::table('eieol_grammar', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
        });
        Schema::table('eieol_head_word', function (Blueprint $table) {
            $table->dropForeign(['etyma_id']);
            $table->dropForeign(['language_id']);
        });
        Schema::table('eieol_lesson', function (Blueprint $table) {
            $table->dropForeign(['series_id']);
            $table->dropForeign(['language_id']);
        });
        Schema::table('lex_etyma', function (Blueprint $table) {
            $table->dropForeign(['lexicon_id']);
        });
        Schema::table('lex_etyma_cross_reference', function (Blueprint $table) {
            $table->dropForeign(['from_etyma_id']);
            $table->dropForeign(['to_etyma_id']);
        });
        Schema::table('lex_etyma_reflex', function (Blueprint $table) {
            $table->dropForeign(['etyma_id']);
            $table->dropForeign(['reflex_id']);
        });
        Schema::table('lex_etyma_semantic_field', function (Blueprint $table) {
            $table->dropForeign(['etyma_id']);
            $table->dropForeign(['semantic_field_id']);
        });
        Schema::table('lex_language', function (Blueprint $table) {
            $table->dropForeign(['sub_family_id']);
        });
        Schema::table('lex_language_sub_family', function (Blueprint $table) {
            $table->dropForeign(['family_id']);
        });
        Schema::table('lex_reflex', function (Blueprint $table) {
            $table->dropForeign(['language_id']);
        });
        Schema::table('lex_reflex_cross_reference', function (Blueprint $table) {
            $table->dropForeign(['from_reflex_id']);
            $table->dropForeign(['to_reflex_id']);
        });
        Schema::table('lex_reflex_part_of_speech', function (Blueprint $table) {
            $table->dropForeign(['reflex_id']);
        });
        Schema::table('lex_reflex_source', function (Blueprint $table) {
            $table->dropForeign(['reflex_id']);
            $table->dropForeign(['source_id']);
        });
        Schema::table('lex_semantic_field', function (Blueprint $table) {
            $table->dropForeign(['semantic_category_id']);
        });
        Schema::table('user_permission', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['eieol_series_id']);
        });

        // update to bigints
        Schema::table('eieol_element', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('gloss_id')->change();
            $table->unsignedBigInteger('head_word_id')->change();
        });
        Schema::table('eieol_gloss', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('glossed_text_id')->change();
            $table->unsignedBigInteger('language_id')->change();
        });
        Schema::table('eieol_glossed_text', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('lesson_id')->change();
        });
        Schema::table('eieol_grammar', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('lesson_id')->change();
        });
        Schema::table('eieol_head_word', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('etyma_id')->change();
            $table->unsignedBigInteger('language_id')->change();
        });
        Schema::table('eieol_language', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::table('eieol_lesson', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('series_id')->change();
            $table->unsignedBigInteger('language_id')->change();
        });
        Schema::table('eieol_series', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::table('eieol_series_language', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::table('lex_etyma', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('lexicon_id')->change();
        });
        Schema::table('lex_etyma_cross_reference', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('from_etyma_id')->change();
            $table->unsignedBigInteger('to_etyma_id')->change();
        });
        Schema::table('lex_etyma_reflex', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('etyma_id')->change();
            $table->unsignedBigInteger('reflex_id')->change();
        });
        Schema::table('lex_etyma_semantic_field', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('etyma_id')->change();
            $table->unsignedBigInteger('semantic_field_id')->change();
        });
        Schema::table('lex_language', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('sub_family_id')->change();
        });
        Schema::table('lex_language_family', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::table('lex_language_sub_family', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('family_id')->change();
        });
        Schema::table('lex_part_of_speech', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::table('lex_reflex', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('language_id')->change();
        });
        Schema::table('lex_reflex_cross_reference', function (Blueprint $table) {
            $table->unsignedBigInteger('from_reflex_id')->change();
            $table->unsignedBigInteger('to_reflex_id')->change();
        });
        Schema::table('lex_reflex_part_of_speech', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('reflex_id')->change();
        });
        Schema::table('lex_reflex_source', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('reflex_id')->change();
            $table->unsignedBigInteger('source_id')->change();
        });
        Schema::table('lex_semantic_category', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::table('lex_semantic_field', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('semantic_category_id')->change();
        });
        Schema::table('lex_source', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::table('link', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::table('menu_item', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::table('page', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::table('user', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::table('user_permission', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->unsignedBigInteger('user_id')->change();
            $table->unsignedBigInteger('eieol_series_id')->change();
        });

        // restore fks
        Schema::table('eieol_element', function (Blueprint $table) {
            $table->foreign('gloss_id')->references('id')->on('eieol_gloss');
            $table->foreign('head_word_id')->references('id')->on('eieol_head_word');
        });
        Schema::table('eieol_gloss', function (Blueprint $table) {
            $table->foreign('glossed_text_id')->references('id')->on('eieol_glossed_text');
            $table->foreign('language_id')->references('id')->on('eieol_language');
        });
        Schema::table('eieol_glossed_text', function (Blueprint $table) {
            $table->foreign('lesson_id')->references('id')->on('eieol_lesson');
        });
        Schema::table('eieol_grammar', function (Blueprint $table) {
            $table->foreign('lesson_id')->references('id')->on('eieol_lesson');
        });
        Schema::table('eieol_head_word', function (Blueprint $table) {
            $table->foreign('etyma_id')->references('id')->on('lex_etyma');
            $table->foreign('language_id')->references('id')->on('eieol_language');
        });
        Schema::table('eieol_lesson', function (Blueprint $table) {
            $table->foreign('series_id')->references('id')->on('eieol_series');
            $table->foreign('language_id')->references('id')->on('eieol_language');
        });
        Schema::table('lex_etyma', function (Blueprint $table) {
            $table->foreign('lexicon_id')->references('id')->on('lex_lexicon');
        });
        Schema::table('lex_etyma_cross_reference', function (Blueprint $table) {
            $table->foreign('from_etyma_id')->references('id')->on('lex_etyma');
            $table->foreign('to_etyma_id')->references('id')->on('lex_etyma');
        });
        Schema::table('lex_etyma_reflex', function (Blueprint $table) {
            $table->foreign('etyma_id')->references('id')->on('lex_etyma');
            $table->foreign('reflex_id')->references('id')->on('lex_reflex');
        });
        Schema::table('lex_etyma_semantic_field', function (Blueprint $table) {
            $table->foreign('etyma_id')->references('id')->on('lex_etyma');
            $table->foreign('semantic_field_id')->references('id')->on('lex_semantic_field');
        });
        Schema::table('lex_language', function (Blueprint $table) {
            $table->foreign('sub_family_id')->references('id')->on('lex_language_sub_family');
        });
        Schema::table('lex_language_sub_family', function (Blueprint $table) {
            $table->foreign('family_id')->references('id')->on('lex_language_family');
        });
        Schema::table('lex_reflex', function (Blueprint $table) {
            $table->foreign('language_id')->references('id')->on('lex_language');
        });
        Schema::table('lex_reflex_cross_reference', function (Blueprint $table) {
            $table->foreign('from_reflex_id')->references('id')->on('lex_reflex');
            $table->foreign('to_reflex_id')->references('id')->on('lex_reflex');
        });
        Schema::table('lex_reflex_part_of_speech', function (Blueprint $table) {
            $table->foreign('reflex_id')->references('id')->on('lex_reflex');
        });
        Schema::table('lex_reflex_source', function (Blueprint $table) {
            $table->foreign('reflex_id')->references('id')->on('lex_reflex');
            $table->foreign('source_id')->references('id')->on('lex_source');
        });
        Schema::table('lex_semantic_field', function (Blueprint $table) {
            $table->foreign('semantic_category_id')->references('id')->on('lex_semantic_category');
        });
        Schema::table('user_permission', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('user');
            $table->foreign('eieol_series_id')->references('id')->on('eieol_series');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // no going back
    }
};
