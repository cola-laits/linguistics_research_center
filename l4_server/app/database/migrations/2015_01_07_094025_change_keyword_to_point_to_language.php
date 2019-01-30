<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeKeywordToPointToLanguage extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_head_word_keyword', function(Blueprint $table)
		{
			$table->integer('language_id')->unsigned()->after('keyword')->default(1);
			$table->foreign('language_id')->references('id')->on('eieol_language');
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
			$table->dropForeign('eieol_head_word_keyword_language_id_foreign');
			$table->dropColumn('language_id');
			
		});
	}

}
