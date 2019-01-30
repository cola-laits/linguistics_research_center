<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEieolElementTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_element', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('gloss_id')->unsigned();
			$table->string('part_of_speech', 191)->nullable();
			$table->string('analysis', 191)->nullable();
			$table->integer('head_word_id')->unsigned()->index('eieol_element_head_word_id_foreign');
			$table->integer('order');
			$table->timestamps();
			$table->string('created_by', 191)->nullable();
			$table->string('updated_by', 191)->nullable();
			$table->index(['gloss_id','order']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('eieol_element');
	}

}
