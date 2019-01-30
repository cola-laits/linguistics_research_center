<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLexLanguageSubFamilyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lex_language_sub_family', function(Blueprint $table)
		{
			$table->foreign('family_id')->references('id')->on('lex_language_family')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lex_language_sub_family', function(Blueprint $table)
		{
			$table->dropForeign('lex_language_sub_family_family_id_foreign');
		});
	}

}
