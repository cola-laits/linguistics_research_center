<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlossTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_gloss', function($table)
		{
			$table->increments('id');
			$table->string('surface_form');
			$table->string('part_of_speech');
			$table->string('analysis');
			$table->integer('head_word_id')->unsigned();
			$table->foreign('head_word_id')->references('id')->on('eieol_head_word');
			$table->string('contextual_gloss');
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
		
			$table->unique(array('surface_form', 'part_of_speech', 'analysis'));
			$table->index('contextual_gloss');
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('eieol_gloss');
	}

}
