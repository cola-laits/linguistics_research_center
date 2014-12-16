<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartOfSpeechTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('part_of_speech', function($table)
		{
			$table->increments('id');
			$table->string('part_of_speech')->unique();
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
			
			$table->index('part_of_speech');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('part_of_speech');
	}

}
	