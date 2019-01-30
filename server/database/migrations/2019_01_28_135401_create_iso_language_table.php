<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIsoLanguageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('iso_language', function(Blueprint $table)
		{
			$table->string('id', 3)->nullable();
			$table->string('Part2B', 3)->nullable();
			$table->string('Part2T', 3)->nullable();
			$table->string('Part1', 2)->nullable();
			$table->string('Scope', 1)->nullable();
			$table->string('Language_Type', 1)->nullable();
			$table->string('Ref_Name', 58)->nullable();
			$table->string('Comment', 42)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('iso_language');
	}

}
