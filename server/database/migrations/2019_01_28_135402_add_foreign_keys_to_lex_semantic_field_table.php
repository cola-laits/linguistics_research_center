<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLexSemanticFieldTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lex_semantic_field', function(Blueprint $table)
		{
			$table->foreign('semantic_category_id')->references('id')->on('lex_semantic_category')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lex_semantic_field', function(Blueprint $table)
		{
			$table->dropForeign('lex_semantic_field_semantic_category_id_foreign');
		});
	}

}
