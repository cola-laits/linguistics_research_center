<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEieolHeadWordKeywordTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_head_word_keyword', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('head_word_id')->unsigned();
			$table->string('keyword', 191)->nullable()->index();
			$table->integer('language_id')->unsigned()->default(1)->index('eieol_head_word_keyword_language_id_foreign');
			$table->timestamps();
			$table->string('created_by', 191)->nullable();
			$table->string('updated_by', 191)->nullable();
			$table->unique(['head_word_id','keyword','language_id']);
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
