<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLexLanguageToAddCustomSort extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lex_language', function(Blueprint $table)
		{
			$table->longText('custom_sort')->nullable()->after('override_family');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lex_language', function(Blueprint $table)
		{
			$table->dropColumn('custom_sort');
		});
	}

}
