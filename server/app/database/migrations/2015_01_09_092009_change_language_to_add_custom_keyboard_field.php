<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLanguageToAddCustomKeyboardField extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_language', function(Blueprint $table)
		{
			$table->longText('custom_keyboard_layout')->nullable()->after('language');
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
			$table->dropColumn('custom_keyboard_layout');
		});
	}

}
