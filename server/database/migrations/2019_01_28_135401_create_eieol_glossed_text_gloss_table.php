<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEieolGlossedTextGlossTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_glossed_text_gloss', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('glossed_text_id')->unsigned();
			$table->integer('gloss_id')->unsigned()->index('eieol_glossed_text_gloss_gloss_id_foreign');
			$table->integer('order');
			$table->timestamps();
			$table->string('created_by', 191)->nullable();
			$table->string('updated_by', 191)->nullable();
			$table->index(['glossed_text_id','order','gloss_id']);
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
