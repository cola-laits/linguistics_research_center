<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLexEtymaSemanticFieldTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lex_etyma_semantic_field', function(Blueprint $table)
		{
			$table->foreign('etyma_id')->references('id')->on('lex_etyma')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('semantic_field_id')->references('id')->on('lex_semantic_field')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lex_etyma_semantic_field', function(Blueprint $table)
		{
			$table->dropForeign('lex_etyma_semantic_field_etyma_id_foreign');
			$table->dropForeign('lex_etyma_semantic_field_semantic_field_id_foreign');
		});
	}

}
