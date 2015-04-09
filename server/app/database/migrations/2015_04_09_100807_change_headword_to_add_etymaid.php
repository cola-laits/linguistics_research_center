<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeHeadwordToAddEtymaid extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_head_word', function(Blueprint $table)
		{
			$table->integer('etyma_id')->unsigned()->nullable()->after('language_id');
			$table->foreign('etyma_id')->references('id')->on('lex_etyma');
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
			$table->dropColumn('etyma_id');
		});
	}

}
