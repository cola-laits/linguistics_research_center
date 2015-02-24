<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLanguageToAddCustomSort extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_language', function(Blueprint $table)
		{
			$table->longText('custom_sort')->nullable()->after('custom_keyboard_layout');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eieol_language', function(Blueprint $table)
		{
			$table->dropColumn('custom_sort');
		});
	}

}