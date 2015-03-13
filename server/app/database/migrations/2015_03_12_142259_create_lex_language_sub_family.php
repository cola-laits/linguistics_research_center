<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLexLanguageSubFamily extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_language_sub_family', function($table)
		{
			$table->increments('id');
			$table->string('name')->unique();
			$table->integer('order')->unique();
			$table->integer('family_id')->unsigned();
			$table->foreign('family_id')->references('id')->on('lex_language_family');
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
		Schema::drop('lex_language_sub_family');
	}

}
