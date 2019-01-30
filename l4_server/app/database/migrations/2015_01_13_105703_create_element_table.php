<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElementTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_element', function($table)
		{
			$table->increments('id');
			$table->integer('gloss_id')->unsigned();
			$table->foreign('gloss_id')->references('id')->on('eieol_gloss');
			$table->string('part_of_speech');
			$table->string('analysis')->nullable();
			$table->integer('head_word_id')->unsigned();
			$table->foreign('head_word_id')->references('id')->on('eieol_head_word');
			$table->integer('order');
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
		
			$table->unique(array('gloss_id', 'part_of_speech', 'analysis'));
			$table->index(array('gloss_id', 'order'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('eieol_element');
	}

}
