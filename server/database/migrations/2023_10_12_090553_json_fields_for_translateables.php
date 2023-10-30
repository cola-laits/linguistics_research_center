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
        Schema::table('lex_language_family', function (Blueprint $table) {
            $table->dropForeign(['lexicon_id']);
            $table->dropIndex(['lexicon_id', 'name', 'order']);
            $table->foreign(['lexicon_id'])->references('id')->on('lex_lexicon');
        });

        Schema::table('lex_language', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        $this->update_to_json('page','name');
        $this->update_to_json('page','content');

        $this->update_to_json('lex_lexicon','protolang_name');
        $this->update_to_json('lex_lexicon','description');

        $this->update_to_json('lex_language_family','name');
        $this->update_to_json('lex_language_sub_family','name');

        $this->update_to_json('lex_language','name');
        $this->update_to_json('lex_language','description');
        $this->update_to_json('lex_etyma','gloss');

        $this->update_to_json('lex_reflex','gloss');
        $this->update_to_json('lex_reflex_cross_reference','relationship');

        $this->update_to_json('lex_semantic_category','text');
        $this->update_to_json('lex_semantic_field','text');

        $this->update_to_json('lex_part_of_speech', 'display');

    /*
     * FIXME decide how to handle these
     *
     * FIXME how to express lex_reflex cross ref relationships as translatable?  Do we need an eloquent model?
     * lex_reflex->crossref->relationship
     * lex_etyma-> extra_data
     * lex_reflex-> extra_data
*/
    }

    public function update_to_json($table, $column) {
        Schema::table($table, function (Blueprint $table) use ($column) {
            $table->mediumtext($column)->change();
        });

        DB::table($table)->orderBy('id')->lazy()->each(function ($row) use ($table, $column) {
            DB::table($table)
                ->where('id', $row->id)
                ->update([
                    $column => json_encode(['en' => $row->$column])
                ]);
        });

        Schema::table($table, function (Blueprint $table) use ($column) {
            $table->json($column)->change();
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
