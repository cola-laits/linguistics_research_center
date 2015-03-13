<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLexEtymaSemantic extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_etyma_semantic_field', function($table)
		{
			$table->increments('id');
			$table->integer('etyma_id')->unsigned();
			$table->foreign('etyma_id')->references('id')->on('lex_etyma');
			$table->integer('semantic_id')->unsigned();
			$table->foreign('semantic_id')->references('id')->on('lex_semantic_field');
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
		Schema::drop('lex_etyma_semantic_field');
	}

}
