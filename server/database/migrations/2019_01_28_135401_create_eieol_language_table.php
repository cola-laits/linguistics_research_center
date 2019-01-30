<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEieolLanguageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_language', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('language', 191)->nullable()->index();
			$table->text('custom_keyboard_layout')->nullable();
			$table->text('substitutions')->nullable();
			$table->text('custom_sort')->nullable();
			$table->string('lang_attribute', 191)->nullable();
			$table->string('class_attribute', 191)->nullable();
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
		Schema::drop('eieol_language');
	}

}
