<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEieolSeriesLanguageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_series_language', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('series_id');
			$table->string('lang', 3);
			$table->string('display');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('eieol_series_language');
	}

}
