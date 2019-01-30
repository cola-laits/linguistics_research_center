<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAnalysisCollation extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
public function up()
	{
		DB::statement('ALTER TABLE eieol_analysis convert to character set utf8 collate utf8_bin;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE eieol_analysis convert to character set utf8 collate utf8_unicode_ci;');
	}

}
