<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEieolGlossedTextGlossTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_glossed_text_gloss', function(Blueprint $table)
		{
			$table->foreign('gloss_id')->references('id')->on('eieol_gloss')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('glossed_text_id')->references('id')->on('eieol_glossed_text')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eieol_glossed_text_gloss', function(Blueprint $table)
		{
			$table->dropForeign('eieol_glossed_text_gloss_gloss_id_foreign');
			$table->dropForeign('eieol_glossed_text_gloss_glossed_text_id_foreign');
		});
	}

}
