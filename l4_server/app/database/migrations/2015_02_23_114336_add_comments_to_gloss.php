<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentsToGloss extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_gloss', function(Blueprint $table)
		{
			$table->string('comments')->after('contextual_gloss')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eieol_gloss', function(Blueprint $table)
		{
			$table->dropColumn('comments');
			
		});
	}

}