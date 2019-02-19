<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveEieolGlossedTextGloss extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $gloss_ids_seen = [];
        $gtgs = DB::select('SELECT * FROM eieol_glossed_text_gloss');
        foreach ($gtgs as $gtg) {
            if (!in_array($gtg->gloss_id, $gloss_ids_seen)) {
                $gloss_ids_seen []= $gtg->gloss_id;
            } else {
                $gloss = DB::select('SELECT * FROM eieol_gloss WHERE id=?', [$gtg->gloss_id])[0];
                DB::insert('INSERT INTO eieol_gloss '.
                    '(surface_form, contextual_gloss,comments,underlying_form,language_id,author_comments,author_done,admin_comments,created_at,updated_at,created_by,updated_by) '.
                    ' VALUES (?,?,?,?,?,?,?,?,?,?,?,?)', [
                        $gloss->surface_form,
                        $gloss->contextual_gloss,
                        $gloss->comments,
                        $gloss->underlying_form,
                        $gloss->language_id,
                        $gloss->author_comments,
                        $gloss->author_done,
                        $gloss->admin_comments,
                        $gloss->created_at,$gloss->updated_at,$gloss->created_by,$gloss->updated_by
                ]);
                $new_gloss_id = DB::getPdo()->lastInsertId();
                DB::update('UPDATE eieol_glossed_text_gloss SET gloss_id=? WHERE id=?', [
                    $new_gloss_id, $gtg->id
                ]);
                $elements = DB::select('SELECT * FROM eieol_element WHERE gloss_id=?', [$gtg->gloss_id]);
                foreach ($elements as $element) {
                    DB::insert('INSERT INTO eieol_element (gloss_id,part_of_speech,analysis,head_word_id,`order`,created_at,updated_at,created_by,updated_by) '.
                        'VALUES (?,?,?,?,?,?,?,?,?)', [
                            $new_gloss_id,
                            $element->part_of_speech,
                            $element->analysis,
                            $element->head_word_id,
                            $element->order,
                            $element->created_at,$element->updated_at,$element->created_by,$element->updated_by
                    ]);
                }
            }
        }

        Schema::table('eieol_glossed_text_gloss', function(Blueprint $table)
        {
            $table->unique('gloss_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // no back-migration
    }
}
