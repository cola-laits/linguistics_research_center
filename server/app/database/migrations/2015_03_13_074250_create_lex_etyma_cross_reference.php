<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLexEtymaCrossReference extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_etyma_cross_reference', function($table)
		{
			$table->increments('id');
			$table->integer('from_etyma_id')->unsigned();
			$table->foreign('from_etyma_id')->references('id')->on('lex_etyma');
			$table->integer('to_etyma_id')->unsigned();
			$table->foreign('to_etyma_id')->references('id')->on('lex_etyma');
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lex_etyma_cross_reference');
	}

}
