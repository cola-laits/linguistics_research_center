<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeadWordTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_head_word', function($table)
		{
			$table->increments('id');
			$table->string('word');
			$table->string('definition');
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
		
			$table->unique(array('word', 'definition'));
			$table->index('word');
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
