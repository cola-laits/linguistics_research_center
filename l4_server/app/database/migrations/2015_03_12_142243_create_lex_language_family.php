<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLexLanguageFamily extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_language_family', function($table)
		{
			$table->increments('id');
			$table->string('name')->unique();
			$table->integer('order')->unique();
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
			
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
		Schema::drop('lex_language_family');
	}

}
