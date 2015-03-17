<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLexEtyma extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_etyma', function($table)
		{
			$table->increments('id');
			$table->string('old_id')->unique();
			$table->integer('order')->unique();
			$table->integer('page_number');
			$table->string('entry')->unique();
			$table->string('gloss');
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
			
			$table->index('order');
		});
		DB::statement('ALTER TABLE lex_etyma convert to character set utf8 collate utf8_bin;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lex_etyma');
	}

}
