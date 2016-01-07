<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveNullablesAnalPos extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE eieol_analysis MODIFY language_id INTEGER UNSIGNED NOT NULL;");
		DB::statement("ALTER TABLE eieol_part_of_speech MODIFY language_id INTEGER UNSIGNED NOT NULL;");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("ALTER TABLE eieol_analysis MODIFY language_id INTEGER UNSIGNED NULL;");
		DB::statement("ALTER TABLE eieol_part_of_speech MODIFY language_id INTEGER UNSIGNED NULL;");
	}

}
