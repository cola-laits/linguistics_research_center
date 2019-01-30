<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLexReflexSourceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_reflex_source', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('reflex_id')->unsigned();
			$table->integer('source_id')->unsigned()->index('lex_reflex_source_source_id_foreign');
			$table->integer('order');
			$table->timestamps();
			$table->unique(['reflex_id','source_id']);
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
