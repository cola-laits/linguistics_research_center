<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('book_section', function (Blueprint $table) {
            $table->dropForeign('book_section_book_id_foreign');
            $table->foreign('book_id')->references('id')->on('book')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('eieol_element', function (Blueprint $table) {
            $table->dropForeign('eieol_element_gloss_id_foreign');
            $table->foreign('gloss_id')->references('id')->on('eieol_gloss')->onUpdate('RESTRICT')->onDelete('cascade');
            $table->dropForeign('eieol_element_head_word_id_foreign');
            $table->foreign('head_word_id')->references('id')->on('eieol_head_word')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('eieol_gloss', function (Blueprint $table) {
            $table->dropForeign('eieol_gloss_language_id_foreign');
            $table->foreign('language_id')->references('id')->on('eieol_language')->onUpdate('RESTRICT')->onDelete('cascade');
            $table->dropForeign('eieol_gloss_glossed_text_id_foreign');
            $table->foreign('glossed_text_id')->references('id')->on('eieol_glossed_text')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('eieol_glossed_text', function (Blueprint $table) {
            $table->dropForeign('eieol_glossed_text_lesson_id_foreign');
            $table->foreign('lesson_id')->references('id')->on('eieol_lesson')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('eieol_grammar', function (Blueprint $table) {
            $table->dropForeign('eieol_grammar_lesson_id_foreign');
            $table->foreign('lesson_id')->references('id')->on('eieol_lesson')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('eieol_head_word', function (Blueprint $table) {
            $table->dropForeign('eieol_head_word_language_id_foreign');
            $table->foreign('language_id')->references('id')->on('eieol_language')->onUpdate('RESTRICT')->onDelete('cascade');
            $table->dropForeign('eieol_head_word_etyma_id_foreign');
            $table->foreign('etyma_id')->references('id')->on('lex_etyma')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('eieol_lesson', function (Blueprint $table) {
            $table->dropForeign('eieol_lesson_language_id_foreign');
            $table->foreign('language_id')->references('id')->on('eieol_language')->onUpdate('RESTRICT')->onDelete('cascade');
            $table->dropForeign('eieol_lesson_series_id_foreign');
            $table->foreign('series_id')->references('id')->on('eieol_series')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('issue_comment', function (Blueprint $table) {
            $table->dropForeign('issue_comment_issue_id_foreign');
            $table->foreign('issue_id')->references('id')->on('issue')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('lex_etyma', function (Blueprint $table) {
            $table->dropForeign('lex_etyma_lexicon_id_foreign');
            $table->foreign('lexicon_id')->references('id')->on('lex_lexicon')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('lex_etyma_reflex', function (Blueprint $table) {
            $table->dropForeign('lex_etyma_reflex_reflex_id_foreign');
            $table->foreign('reflex_id')->references('id')->on('lex_reflex')->onUpdate('RESTRICT')->onDelete('cascade');
            $table->dropForeign('lex_etyma_reflex_etyma_id_foreign');
            $table->foreign('etyma_id')->references('id')->on('lex_etyma')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('lex_etyma_cross_reference', function (Blueprint $table) {
            $table->dropForeign('lex_etyma_cross_reference_from_etyma_id_foreign');
            $table->foreign('from_etyma_id')->references('id')->on('lex_etyma')->onUpdate('RESTRICT')->onDelete('cascade');
            $table->dropForeign('lex_etyma_cross_reference_to_etyma_id_foreign');
            $table->foreign('to_etyma_id')->references('id')->on('lex_etyma')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('lex_etyma_extra_data', function (Blueprint $table) {
            $table->dropForeign('lex_etyma_extra_data_etyma_id_foreign');
            $table->foreign('etyma_id')->references('id')->on('lex_etyma')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('lex_etyma_semantic_field', function (Blueprint $table) {
            $table->dropForeign('lex_etyma_semantic_field_etyma_id_foreign');
            $table->foreign('etyma_id')->references('id')->on('lex_etyma')->onUpdate('RESTRICT')->onDelete('cascade');
            $table->dropForeign('lex_etyma_semantic_field_semantic_field_id_foreign');
            $table->foreign('semantic_field_id')->references('id')->on('lex_semantic_field')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('lex_language', function (Blueprint $table) {
            $table->dropForeign('lex_language_sub_family_id_foreign');
            $table->foreign('sub_family_id')->references('id')->on('lex_language_sub_family')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('lex_language_sub_family', function (Blueprint $table) {
            $table->dropForeign('lex_language_sub_family_family_id_foreign');
            $table->foreign('family_id')->references('id')->on('lex_language_family')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('lex_language_family', function (Blueprint $table) {
            $table->dropForeign('lex_language_family_lexicon_id_foreign');
            $table->foreign('lexicon_id')->references('id')->on('lex_lexicon')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('lex_part_of_speech', function (Blueprint $table) {
            $table->dropForeign('lex_part_of_speech_lexicon_id_foreign');
            $table->foreign('lexicon_id')->references('id')->on('lex_lexicon')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('lex_reflex', function (Blueprint $table) {
            $table->dropForeign('lex_reflex_language_id_foreign');
            $table->foreign('language_id')->references('id')->on('lex_language')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('lex_reflex_part_of_speech', function (Blueprint $table) {
            $table->dropForeign('lex_reflex_part_of_speech_reflex_id_foreign');
            $table->foreign('reflex_id')->references('id')->on('lex_reflex')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('lex_reflex_cross_reference', function (Blueprint $table) {
            $table->dropForeign('lex_reflex_cross_reference_from_reflex_id_foreign');
            $table->foreign('from_reflex_id')->references('id')->on('lex_reflex')->onUpdate('RESTRICT')->onDelete('cascade');
            $table->dropForeign('lex_reflex_cross_reference_to_reflex_id_foreign');
            $table->foreign('to_reflex_id')->references('id')->on('lex_reflex')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('lex_reflex_extra_data', function (Blueprint $table) {
            $table->dropForeign('lex_reflex_extra_data_reflex_id_foreign');
            $table->foreign('reflex_id')->references('id')->on('lex_reflex')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('lex_reflex_source', function (Blueprint $table) {
            $table->dropForeign('lex_reflex_source_reflex_id_foreign');
            $table->foreign('reflex_id')->references('id')->on('lex_reflex')->onUpdate('RESTRICT')->onDelete('cascade');
            $table->dropForeign('lex_reflex_source_source_id_foreign');
            $table->foreign('source_id')->references('id')->on('lex_source')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('lex_semantic_category', function (Blueprint $table) {
            $table->dropForeign('lex_semantic_category_lexicon_id_foreign');
            $table->foreign('lexicon_id')->references('id')->on('lex_lexicon')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('lex_semantic_field', function (Blueprint $table) {
            $table->dropForeign('lex_semantic_field_semantic_category_id_foreign');
            $table->foreign('semantic_category_id')->references('id')->on('lex_semantic_category')->onUpdate('RESTRICT')->onDelete('cascade');
        });

        Schema::table('lex_source', function (Blueprint $table) {
            $table->dropForeign('lex_source_lexicon_id_foreign');
            $table->foreign('lexicon_id')->references('id')->on('lex_lexicon')->onUpdate('RESTRICT')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
