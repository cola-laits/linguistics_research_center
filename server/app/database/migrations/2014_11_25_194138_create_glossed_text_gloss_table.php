<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlossedTextGlossTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_glossed_text_gloss', function($table)
		{
			$table->increments('id');
			$table->integer('glossed_text_id')->unsigned();
			$table->foreign('glossed_text_id')->references('id')->on('eieol_glossed_text');
			$table->integer('gloss_id')->unsigned();
			$table->foreign('gloss_id')->references('id')->on('eieol_gloss');
			$table->integer('order');
			$table->timestamps();
			$table->string('created_by');
			$table->string('updated_by');
		
			$table->index(array('glossed_text_id', 'order', 'gloss_id'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('eieol_glossed_text_gloss');
	}

}
