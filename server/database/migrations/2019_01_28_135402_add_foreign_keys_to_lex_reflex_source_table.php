<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLexReflexSourceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lex_reflex_source', function(Blueprint $table)
		{
			$table->foreign('reflex_id')->references('id')->on('lex_reflex')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('source_id')->references('id')->on('lex_source')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lex_reflex_source', function(Blueprint $table)
		{
			$table->dropForeign('lex_reflex_source_reflex_id_foreign');
			$table->dropForeign('lex_reflex_source_source_id_foreign');
		});
	}

}
