<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEieolGlossTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_gloss', function(Blueprint $table)
		{
			$table->foreign('language_id')->references('id')->on('eieol_language')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
			$table->dropForeign('eieol_gloss_language_id_foreign');
		});
	}

}
