<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLexLanguageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_language', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191)->nullable()->unique();
			$table->integer('order')->index();
			$table->string('abbr', 191)->nullable()->unique();
			$table->string('aka', 191)->nullable();
			$table->integer('sub_family_id')->unsigned();
			$table->string('override_family', 191)->nullable();
			$table->text('custom_sort')->nullable();
			$table->timestamps();
			$table->string('created_by', 191)->nullable();
			$table->string('updated_by', 191)->nullable();
			$table->index(['sub_family_id','order']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lex_language');
	}

}
