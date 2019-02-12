<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLexReflexEntryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lex_reflex_entry', function(Blueprint $table)
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
		Schema::table('lex_reflex_entry', function(Blueprint $table)
		{
			$table->dropForeign('lex_reflex_entry_reflex_id_foreign');
		});
	}

}
