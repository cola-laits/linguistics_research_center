<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLexReflexPartOfSpeechTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lex_reflex_part_of_speech', function(Blueprint $table)
		{
			$table->foreign('reflex_id')->references('id')->on('lex_reflex')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lex_reflex_part_of_speech', function(Blueprint $table)
		{
			$table->dropForeign('lex_reflex_part_of_speech_reflex_id_foreign');
		});
	}

}
