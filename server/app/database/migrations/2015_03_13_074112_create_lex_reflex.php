<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLexReflex extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_reflex', function($table)
		{
			$table->increments('id');
			$table->integer('language_id')->unsigned();
			$table->foreign('language_id')->references('id')->on('lex_language');
			$table->integer('source_id')->unsigned();
			$table->foreign('source_id')->references('id')->on('lex_source');
			$table->integer('part_of_speech_id')->unsigned();
			$table->foreign('part_of_speech_id')->references('id')->on('lex_part_of_speech');
			$table->string('reflex');
			$table->string('lang_attribute');
			$table->string('class_attribute');
			$table->string('gloss');
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
			
			$table->index('language_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lex_reflex');
	}

}
