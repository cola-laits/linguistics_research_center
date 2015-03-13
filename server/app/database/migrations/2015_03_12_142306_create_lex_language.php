<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLexLanguage extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_language', function($table)
		{
			$table->increments('id');
			$table->string('name')->unique();
			$table->integer('order')->unique();
			$table->string('abbr')->unique();
			$table->string('aka');
			$table->integer('sub_family_id')->unsigned();
			$table->foreign('sub_family_id')->references('id')->on('lex_language_sub_family');
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
		Schema::drop('lex_language');
	}

}
