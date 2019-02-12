<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLexEtymaSemanticFieldTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_etyma_semantic_field', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('etyma_id')->unsigned()->index('lex_etyma_semantic_field_etyma_id_foreign');
			$table->integer('semantic_field_id')->unsigned()->index('lex_etyma_semantic_field_semantic_field_id_foreign');
			$table->timestamps();
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
