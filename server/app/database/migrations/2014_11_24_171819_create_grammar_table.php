<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrammarTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_grammar', function($table)
		{
			$table->increments('id');
			$table->integer('lesson_id')->unsigned();
			$table->foreign('lesson_id')->references('id')->on('eieol_lesson');
			$table->string('title');
			$table->integer('order');
			$table->longText('grammar_text');
			$table->string('section_number');
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
				
			$table->unique(array('lesson_id', 'order'));
			$table->unique(array('lesson_id', 'section_number'));
			$table->index('order');
			$table->index('section_number');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('eieol_grammar');
	}

}
