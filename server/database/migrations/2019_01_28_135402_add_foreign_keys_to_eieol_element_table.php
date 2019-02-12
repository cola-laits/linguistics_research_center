<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEieolElementTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_element', function(Blueprint $table)
		{
			$table->foreign('gloss_id')->references('id')->on('eieol_gloss')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('head_word_id')->references('id')->on('eieol_head_word')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
			$table->dropForeign('eieol_element_gloss_id_foreign');
			$table->dropForeign('eieol_element_head_word_id_foreign');
		});
	}

}
