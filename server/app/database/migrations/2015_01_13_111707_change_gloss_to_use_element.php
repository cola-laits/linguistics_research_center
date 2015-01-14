<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeGlossToUseElement extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_gloss', function(Blueprint $table)
		{
			$table->dropForeign('eieol_gloss_head_word_id_foreign');
			$table->dropUnique('eieol_gloss_surface_form_part_of_speech_analysis_unique');
			$table->dropColumn('part_of_speech');
			$table->dropColumn('analysis');
			$table->dropColumn('head_word_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eieol_gloss', function(Blueprint $table)
		{
			$table->string('part_of_speech')->after('surface_form');
			$table->string('analysis')->nullable()->after('part_of_speech');
			$table->integer('head_word_id')->unsigned()->after('head_word_id');
			$table->foreign('head_word_id')->references('id')->on('eieol_head_word');
			$table->unique(array('surface_form', 'part_of_speech', 'analysis'));
		});
	}

}
