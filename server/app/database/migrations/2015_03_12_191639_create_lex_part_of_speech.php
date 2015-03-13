<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLexPartOfSpeech extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
public function up()
	{
		Schema::create('lex_part_of_speech', function($table)
		{
			$table->increments('id');
			$table->string('code')->unique();
			$table->string('display')->unique();
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
			
			$table->index('code');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lex_part_of_speech');
	}

}
