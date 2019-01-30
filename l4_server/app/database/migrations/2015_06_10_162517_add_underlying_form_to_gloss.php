<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnderlyingFormToGloss extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_gloss', function(Blueprint $table)
		{
			$table->string('underlying_form')->after('comments')->nullable();
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
			$table->dropColumn('underlying_form');
			
		});
	}

}
