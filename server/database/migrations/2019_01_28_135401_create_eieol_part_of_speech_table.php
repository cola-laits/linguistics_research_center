<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEieolPartOfSpeechTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_part_of_speech', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('part_of_speech', 191)->nullable()->index('part_of_speech_part_of_speech_index');
			$table->integer('language_id')->unsigned()->index('eieol_part_of_speech_language_id_foreign');
			$table->timestamps();
			$table->string('created_by', 191)->nullable();
			$table->string('updated_by', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('eieol_part_of_speech');
	}

}
