<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSeriesPublishedToNull extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE eieol_series MODIFY published INTEGER UNSIGNED NULL;");
    	DB::statement("UPDATE eieol_series SET published = NULL WHERE published = 0;");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("UPDATE eieol_series SET published = 0 WHERE published IS NULL;");
		DB::statement("ALTER TABLE eieol_series MODIFY published INTEGER UNSIGNED NOT NULL;");
	}

}
