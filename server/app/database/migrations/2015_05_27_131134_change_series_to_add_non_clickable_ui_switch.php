<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSeriesToAddNonClickableUiSwitch extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_series', function(Blueprint $table)
		{
			$table->boolean('use_old_gloss_ui')->after('published')->nullable()->default(False);
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
			$table->dropColumn('use_old_gloss_ui');
			
		});
	}

}
