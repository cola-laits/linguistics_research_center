<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEieolPartOfSpeechTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_part_of_speech', function(Blueprint $table)
		{
			$table->foreign('language_id')->references('id')->on('eieol_language')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eieol_part_of_speech', function(Blueprint $table)
		{
			$table->dropForeign('eieol_part_of_speech_language_id_foreign');
		});
	}

}
