<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentsToLesson extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_lesson', function(Blueprint $table)
		{
			$table->longText('author_comments')->after('lesson_translation')->nullable();
			$table->longText('admin_comments')->after('author_comments')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eieol_lesson', function(Blueprint $table)
		{
			$table->dropColumn('author_comments');
			$table->dropColumn('admin_comments');
		});
	}

}
