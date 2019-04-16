<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveEieolGlossedTextGlossPart2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eieol_gloss', function (Blueprint $table) {
            $table->integer('glossed_text_id')
                ->unsigned()
                ->nullable();
            $table->integer('order')
                ->nullable();

            $table->foreign('glossed_text_id')->references('id')->on('eieol_glossed_text');
        });

        DB::update('UPDATE eieol_glossed_text_gloss,eieol_gloss '
            .'SET eieol_gloss.order=eieol_glossed_text_gloss.order, eieol_gloss.glossed_text_id=eieol_glossed_text_gloss.glossed_text_id'
            .' WHERE eieol_gloss.id=eieol_glossed_text_gloss.gloss_id');

        Schema::drop('eieol_glossed_text_gloss');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // FIXME not easy to go back from this one...
    }
}
