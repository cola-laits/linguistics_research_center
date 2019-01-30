<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEieolSeriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_series', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title', 191)->nullable()->unique();
			$table->string('slug', 191)->nullable();
			$table->integer('order')->index();
			$table->string('menu_name', 191)->nullable();
			$table->string('menu_order', 191)->nullable()->index();
			$table->string('expanded_title', 191)->nullable();
			$table->integer('published')->unsigned()->nullable();
			$table->boolean('use_old_gloss_ui')->nullable()->default(0);
			$table->text('meta_tags', 65535)->nullable();
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
		Schema::drop('eieol_series');
	}

}
