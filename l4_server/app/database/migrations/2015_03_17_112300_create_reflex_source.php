<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReflexSource extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_reflex_source', function($table)
		{
			$table->increments('id');
			$table->integer('reflex_id')->unsigned();
			$table->foreign('reflex_id')->references('id')->on('lex_reflex');
			$table->integer('source_id')->unsigned();
			$table->foreign('source_id')->references('id')->on('lex_source');
			$table->integer('order');
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
			
			$table->unique(array('reflex_id','source_id'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lex_reflex_source');
	}

}
