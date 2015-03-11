<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeKeywordUniqueness extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_head_word_keyword', function(Blueprint $table)
		{
			$table->unique(array('head_word_id', 'keyword', 'language_id'));
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
			$table->dropUnique('eieol_head_word_keyword_head_word_id_keyword_language_id_unique');
		});
	}

}
