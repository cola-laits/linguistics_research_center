<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_lesson', function($table)
		{
			$table->increments('id');
			$table->integer('series_id')->unsigned();
			$table->foreign('series_id')->references('id')->on('eieol_series');
			$table->string('title');
			$table->integer('order');
			$table->longText('intro_text');
			$table->longText('lesson_translation');
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
			
			$table->unique(array('series_id', 'order'));
			$table->index('order');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('eieol_lesson');
	}

}
