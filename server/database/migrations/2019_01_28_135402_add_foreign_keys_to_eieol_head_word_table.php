<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEieolHeadWordTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_head_word', function(Blueprint $table)
		{
			$table->foreign('etyma_id')->references('id')->on('lex_etyma')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('language_id')->references('id')->on('eieol_language')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eieol_head_word', function(Blueprint $table)
		{
			$table->dropForeign('eieol_head_word_etyma_id_foreign');
			$table->dropForeign('eieol_head_word_language_id_foreign');
		});
	}

}
