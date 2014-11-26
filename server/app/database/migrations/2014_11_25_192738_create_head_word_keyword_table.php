<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeadWordKeywordTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_head_word_keyword', function($table)
		{
			$table->increments('id');
			$table->integer('head_word_id')->unsigned();
			$table->foreign('head_word_id')->references('id')->on('eieol_head_word');
			$table->string('keyword');
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');

			$table->index('keyword');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('eieol_head_word_keyword');
	}

}
