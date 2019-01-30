<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLexSemanticCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_semantic_category', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('text', 191)->nullable()->unique();
			$table->string('number', 191)->nullable()->index();
			$table->string('abbr', 191)->nullable()->unique();
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
		Schema::drop('lex_semantic_category');
	}

}
