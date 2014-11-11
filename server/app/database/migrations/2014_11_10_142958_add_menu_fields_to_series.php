<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMenuFieldsToSeries extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_series', function(Blueprint $table)
		{
			$table->string('menu_name')->after('order');
			$table->string('menu_order')->after('menu_name');
			$table->string('expanded_title')->after('menu_order');
			$table->index('menu_order');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eieol_series', function(Blueprint $table)
		{
			$table->dropColumn('menu_name');
			$table->dropColumn('menu_order');
			$table->dropColumn('expanded_title');
			
		});
	}

}
