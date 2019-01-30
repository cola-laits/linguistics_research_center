<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEieolHeadWordTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_head_word', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('word', 191)->nullable()->index();
			$table->string('definition', 191)->nullable();
			$table->integer('language_id')->unsigned()->default(1)->index('eieol_head_word_language_id_foreign');
			$table->integer('etyma_id')->unsigned()->nullable()->index('eieol_head_word_etyma_id_foreign');
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
		Schema::drop('eieol_head_word');
	}

}
