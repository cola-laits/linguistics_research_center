<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLexPartOfSpeechTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_part_of_speech', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code', 191)->nullable()->unique();
			$table->string('display', 191)->nullable();
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
		Schema::drop('lex_part_of_speech');
	}

}
