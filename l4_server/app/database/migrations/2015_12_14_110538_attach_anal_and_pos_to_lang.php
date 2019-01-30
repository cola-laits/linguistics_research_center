<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AttachAnalAndPosToLang extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_analysis', function(Blueprint $table)
		{
			$table->dropunique('eieol_analysis_analysis_unique');
			$table->integer('language_id')->unsigned()->after('analysis')->nullable();
			$table->foreign('language_id')->references('id')->on('eieol_language');
		});
		Schema::table('eieol_part_of_speech', function(Blueprint $table)
		{
			$table->dropunique('part_of_speech_part_of_speech_unique');
			$table->integer('language_id')->unsigned()->after('part_of_speech')->nullable();
			$table->foreign('language_id')->references('id')->on('eieol_language');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eieol_analysis', function(Blueprint $table)
		{
			$table->dropForeign('eieol_analysis_language_id_foreign');
			$table->dropColumn('language_id');
			$table->unique('analysis');
		});
		Schema::table('eieol_part_of_speech', function(Blueprint $table)
		{
			$table->dropForeign('eieol_part_of_speech_language_id_foreign');
			$table->dropColumn('language_id');
			$table->unique('part_of_speech');
		});
	}

}