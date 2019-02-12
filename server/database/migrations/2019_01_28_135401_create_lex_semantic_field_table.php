<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLexSemanticFieldTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_semantic_field', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('text', 191)->nullable()->unique();
			$table->string('number', 191)->nullable()->index();
			$table->string('abbr', 191)->nullable()->unique();
			$table->integer('semantic_category_id')->unsigned()->index('lex_semantic_field_semantic_category_id_foreign');
			$table->timestamps();
			$table->string('created_by', 191)->nullable();
			$table->string('updated_by', 191)->nullable();
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
