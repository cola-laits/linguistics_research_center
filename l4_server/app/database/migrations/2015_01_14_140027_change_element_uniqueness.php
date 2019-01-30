<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeElementUniqueness extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_element', function(Blueprint $table)
		{
			$table->dropUnique('eieol_element_gloss_id_part_of_speech_analysis_unique');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eieol_element', function(Blueprint $table)
		{	
			$table->unique(array('gloss_id', 'part_of_speech', 'analysis'));
		});
	}

}
