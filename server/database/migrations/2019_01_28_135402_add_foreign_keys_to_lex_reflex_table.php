<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLexReflexTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lex_reflex', function(Blueprint $table)
		{
			$table->foreign('language_id')->references('id')->on('lex_language')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lex_reflex', function(Blueprint $table)
		{
			$table->dropForeign('lex_reflex_language_id_foreign');
		});
	}

}
