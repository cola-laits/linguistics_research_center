<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReflexPos extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_reflex_part_of_speech', function($table)
		{
			$table->increments('id');
			$table->integer('reflex_id')->unsigned();
			$table->foreign('reflex_id')->references('id')->on('lex_reflex');
			$table->integer('part_of_speech_id')->unsigned();
			$table->foreign('part_of_speech_id')->references('id')->on('lex_part_of_speech');
			$table->integer('order');
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
			
			$table->unique(array('reflex_id','part_of_speech_id'));
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
