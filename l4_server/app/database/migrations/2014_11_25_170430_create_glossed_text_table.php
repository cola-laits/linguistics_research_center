<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlossedTextTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_glossed_text', function($table)
		{
			$table->increments('id');
			$table->integer('lesson_id')->unsigned();
			$table->foreign('lesson_id')->references('id')->on('eieol_lesson');
			$table->text('glossed_text');
			$table->integer('order');
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
		
			$table->unique(array('lesson_id', 'order'));
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
		Schema::drop('eieol_glossed_text');
	}

}
