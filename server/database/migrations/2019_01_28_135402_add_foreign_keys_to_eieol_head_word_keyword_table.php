<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEieolHeadWordKeywordTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_head_word_keyword', function(Blueprint $table)
		{
			$table->foreign('head_word_id')->references('id')->on('eieol_head_word')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
		Schema::table('eieol_head_word_keyword', function(Blueprint $table)
		{
			$table->dropForeign('eieol_head_word_keyword_head_word_id_foreign');
			$table->dropForeign('eieol_head_word_keyword_language_id_foreign');
		});
	}

}
