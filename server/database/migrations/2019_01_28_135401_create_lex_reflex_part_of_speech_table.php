<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLexReflexPartOfSpeechTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_reflex_part_of_speech', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('reflex_id')->unsigned();
			$table->string('text', 191)->nullable();
			$table->integer('order');
			$table->timestamps();
			$table->string('created_by', 191)->nullable();
			$table->string('updated_by', 191)->nullable();
			$table->unique(['reflex_id','text']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lex_reflex_part_of_speech');
	}

}
