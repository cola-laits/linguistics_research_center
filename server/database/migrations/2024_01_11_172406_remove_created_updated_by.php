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
        $table_names = ['eieol_element', 'eieol_gloss', 'eieol_glossed_text', 'eieol_grammar',
            'eieol_head_word', 'eieol_language', 'eieol_lesson', 'eieol_series',
            'lex_etyma', 'lex_language', 'lex_language_family', 'lex_language_sub_family',
            'lex_part_of_speech', 'lex_reflex', 'lex_reflex_part_of_speech',
            'lex_semantic_category', 'lex_semantic_field',  'lex_source', 'user', 'user_permission'];
        foreach ($table_names as $table_name) {
            Schema::table($table_name, function (Blueprint $table) {
                $table->dropColumn(['created_by']);
                $table->dropColumn(['updated_by']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table_names = ['eieol_element', 'eieol_gloss', 'eieol_glossed_text', 'eieol_grammar',
            'eieol_head_word', 'eieol_language', 'eieol_lesson', 'eieol_series',
            'lex_etyma', 'lex_language', 'lex_language_family', 'lex_language_sub_family',
            'lex_part_of_speech', 'lex_reflex', 'lex_reflex_part_of_speech',
            'lex_semantic_category', 'lex_semantic_field',  'lex_source', 'user', 'user_permission'];
        foreach ($table_names as $table_name) {
            Schema::table($table_name, function (Blueprint $table) {
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
            });
        }
    }
};
