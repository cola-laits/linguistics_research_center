<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEieolGlossTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_gloss', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('surface_form', 191)->nullable();
			$table->string('contextual_gloss', 191)->nullable()->index();
			$table->text('comments', 65535)->nullable();
			$table->string('underlying_form', 191)->nullable();
			$table->integer('language_id')->unsigned()->default(1)->index('eieol_gloss_language_id_foreign');
			$table->text('author_comments')->nullable();
			$table->boolean('author_done')->nullable();
			$table->text('admin_comments')->nullable();
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
		Schema::drop('eieol_gloss');
	}

}
