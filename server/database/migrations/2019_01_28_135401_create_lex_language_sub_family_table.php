<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLexLanguageSubFamilyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_language_sub_family', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191)->nullable();
			$table->integer('order')->index();
			$table->integer('family_id')->unsigned()->index('lex_language_sub_family_family_id_foreign');
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
		Schema::drop('lex_language_sub_family');
	}

}
