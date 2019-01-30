<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEieolGrammarTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_grammar', function(Blueprint $table)
		{
			$table->foreign('lesson_id')->references('id')->on('eieol_lesson')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eieol_grammar', function(Blueprint $table)
		{
			$table->dropForeign('eieol_grammar_lesson_id_foreign');
		});
	}

}
