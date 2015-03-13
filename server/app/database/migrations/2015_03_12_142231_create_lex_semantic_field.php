<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLexSemanticField extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_semantic_field', function($table)
		{
			$table->increments('id');
			$table->string('text')->unique();
			$table->string('number')->unique();
			$table->string('abbr')->unique();
			$table->integer('semantic_category_id')->unsigned();
			$table->foreign('semantic_category_id')->references('id')->on('lex_semantic_category');
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
			
			$table->index('number');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lex_semantic_field');
	}

}
