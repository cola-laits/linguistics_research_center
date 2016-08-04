<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeriesSlug extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                if (!Schema::hasColumn('eieol_series', 'slug')) {
                	Schema::table('eieol_series', function(Blueprint $table)
                	{
                        	$table->string('slug', 100)->after('title');
                	});
		}
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {

	}

}
