<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLanguageSubstitution extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_language', function(Blueprint $table)
		{
			$table->longText('substitutions')->after('custom_keyboard_layout')->nullable();
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
			$table->dropColumn('substitutions');
		});
	}

}
