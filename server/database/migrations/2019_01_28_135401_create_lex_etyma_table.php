<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLexEtymaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_etyma', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('old_id', 191)->nullable()->unique();
			$table->integer('order')->unique();
			$table->string('page_number', 191)->nullable();
			$table->string('entry', 191)->nullable()->unique();
			$table->string('gloss', 191)->nullable();
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
		Schema::drop('lex_etyma');
	}

}
