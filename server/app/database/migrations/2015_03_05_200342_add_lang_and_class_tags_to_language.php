<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLangAndClassTagsToLanguage extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_language', function(Blueprint $table)
		{
			$table->string('lang_attribute')->after('custom_sort');
			$table->string('class_attribute')->after('lang_attribute');
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
			$table->dropColumn('lang_attribute');
			$table->dropColumn('class_attribute');
		});
	}

}
