<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLexReflex extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_reflex', function($table)
		{
			$table->increments('id');
			$table->integer('language_id')->unsigned();
			$table->foreign('language_id')->references('id')->on('lex_language');
			$table->string('reflex');
			$table->string('lang_attribute');
			$table->string('class_attribute');
			$table->string('gloss');
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
			
			$table->index('language_id');
		});
		DB::statement('ALTER TABLE lex_reflex convert to character set utf8 collate utf8_bin;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lex_reflex');
	}

}
