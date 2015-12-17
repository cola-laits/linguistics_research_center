<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorDone extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_lesson', function(Blueprint $table)
		{
			$table->boolean('author_done')->after('author_comments')->nullable();
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
			$table->dropColumn('author_done');
		});
	}

}
