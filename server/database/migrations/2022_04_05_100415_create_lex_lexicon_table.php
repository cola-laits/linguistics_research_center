<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lex_lexicon', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        DB::table('lex_lexicon')->insert([
            'id'=>1,
            'slug'=>'ielex',
            'name'=>'IELEX'
        ]);
        DB::table('lex_lexicon')->insert([
            'id'=>2,
            'slug'=>'semitilex',
            'name'=>'SEMITILEX'
        ]);

        foreach (['lex_part_of_speech','lex_language_family','lex_semantic_category','lex_source','lex_etyma'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->unsignedBigInteger('lexicon_id');
            });
        }

        Schema::table('lex_part_of_speech', function(Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropIndex(['code']);
            $table->index(['lexicon_id', 'code']);
        });
        Schema::table('lex_language_family', function(Blueprint $table) {
            $table->dropUnique(['name']);
            $table->dropUnique(['order']);
            $table->dropIndex(['order']);
            $table->index(['lexicon_id', 'name', 'order']);
        });
        Schema::table('lex_semantic_category', function(Blueprint $table) {
            $table->dropUnique(['abbr']);
            $table->dropUnique(['number']);
            $table->dropUnique(['text']);
            $table->dropIndex(['number']);
            $table->index(['lexicon_id', 'abbr']);
        });
        Schema::table('lex_source', function(Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropUnique(['display']);
            $table->dropIndex(['code']);
            $table->index(['lexicon_id', 'code']);
        });
        Schema::table('lex_etyma', function(Blueprint $table) {
            $table->dropUnique(['order']);
            $table->dropUnique(['old_id']);
            $table->dropIndex(['order']);
            $table->index(['lexicon_id', 'order']);
        });

        foreach (['lex_part_of_speech','lex_language_family','lex_semantic_category','lex_source','lex_etyma'] as $table) {
            DB::table($table)->update(['lexicon_id'=>1]);

            Schema::table($table, function (Blueprint $table) {
                $table->foreign('lexicon_id')->references('id')->on('lex_lexicon');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
