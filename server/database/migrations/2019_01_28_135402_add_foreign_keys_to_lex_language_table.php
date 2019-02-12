<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLexLanguageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lex_language', function(Blueprint $table)
		{
			$table->foreign('sub_family_id')->references('id')->on('lex_language_sub_family')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lex_language', function(Blueprint $table)
		{
			$table->dropForeign('lex_language_sub_family_id_foreign');
		});
	}

}
