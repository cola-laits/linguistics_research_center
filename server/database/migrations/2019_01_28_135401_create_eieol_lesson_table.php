<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEieolLessonTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_lesson', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('series_id')->unsigned();
			$table->string('title', 191)->nullable();
			$table->integer('order')->index();
			$table->integer('language_id')->unsigned()->default(1)->index('eieol_lesson_language_id_foreign');
			$table->text('intro_text')->nullable();
			$table->text('lesson_translation')->nullable();
			$table->text('author_comments')->nullable();
			$table->boolean('author_done')->nullable();
			$table->text('admin_comments')->nullable();
			$table->text('translation_author_comments')->nullable();
			$table->boolean('translation_author_done')->nullable();
			$table->text('translation_admin_comments')->nullable();
			$table->timestamps();
			$table->string('created_by', 191)->nullable();
			$table->string('updated_by', 191)->nullable();
			$table->unique(['series_id','order']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('eieol_lesson');
	}

}
