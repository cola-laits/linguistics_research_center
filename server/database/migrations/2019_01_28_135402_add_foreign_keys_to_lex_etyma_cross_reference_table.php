<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLexEtymaCrossReferenceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lex_etyma_cross_reference', function(Blueprint $table)
		{
			$table->foreign('from_etyma_id')->references('id')->on('lex_etyma')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('to_etyma_id')->references('id')->on('lex_etyma')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lex_etyma_cross_reference', function(Blueprint $table)
		{
			$table->dropForeign('lex_etyma_cross_reference_from_etyma_id_foreign');
			$table->dropForeign('lex_etyma_cross_reference_to_etyma_id_foreign');
		});
	}

}
