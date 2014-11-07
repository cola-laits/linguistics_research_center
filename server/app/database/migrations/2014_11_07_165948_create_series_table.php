<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_series', function($table)
		{
			$table->increments('id');
			$table->string('title')->unique();
			$table->integer('order');
			$table->boolean('published');
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
			
			$table->index('order');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('eieol_series');
	}

}
