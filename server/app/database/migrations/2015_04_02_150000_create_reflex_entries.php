<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReflexEntries extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_reflex_entry', function($table)
		{
			$table->increments('id');
			$table->integer('reflex_id')->unsigned();
			$table->foreign('reflex_id')->references('id')->on('lex_reflex');
			$table->string('entry');
			$table->integer('order');
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
			
			$table->index('reflex_id');
			
		});
		DB::statement('ALTER TABLE lex_reflex_entry convert to character set utf8 collate utf8_bin;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lex_reflex_entry');
	}

}
