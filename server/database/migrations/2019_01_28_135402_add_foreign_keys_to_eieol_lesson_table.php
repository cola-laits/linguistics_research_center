<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEieolLessonTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_lesson', function(Blueprint $table)
		{
			$table->foreign('language_id')->references('id')->on('eieol_language')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('series_id')->references('id')->on('eieol_series')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eieol_lesson', function(Blueprint $table)
		{
			$table->dropForeign('eieol_lesson_language_id_foreign');
			$table->dropForeign('eieol_lesson_series_id_foreign');
		});
	}

}
