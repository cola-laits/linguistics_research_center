<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEieolSeriesMetaTags extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::table('eieol_series', function(Blueprint $table)
                {
                        $table->text('meta_tags')->after('use_old_gloss_ui');
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
                        $table->dropColumn('meta_tags');
                });
	}

}
