<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEieolGlossedTextTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_glossed_text', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('lesson_id')->unsigned();
			$table->text('glossed_text', 65535)->nullable();
			$table->integer('order')->index();
			$table->text('author_comments')->nullable();
			$table->boolean('author_done')->nullable();
			$table->text('admin_comments')->nullable();
			$table->timestamps();
			$table->string('created_by', 191)->nullable();
			$table->string('updated_by', 191)->nullable();
			$table->unique(['lesson_id','order']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('eieol_glossed_text');
	}

}
