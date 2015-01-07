<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguageTable extends Migration {

		/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_language', function($table)
		{
			$table->increments('id');
			$table->string('language')->unique();
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
			
			$table->index('language');
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