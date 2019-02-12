<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('page', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('slug', 191)->nullable()->unique('eieol_glossed_text_lesson_id_order_unique');
			$table->string('name', 191)->nullable();
			$table->text('content', 16777215)->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('page');
	}

}
