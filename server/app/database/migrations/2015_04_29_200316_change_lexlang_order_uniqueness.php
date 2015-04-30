<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLexlangOrderUniqueness extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lex_language', function(Blueprint $table)
		{
			$table->index(array('sub_family_id', 'order'));
			$table->dropUnique('lex_language_sub_family_id_order_unique');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lex_language', function(Blueprint $table)
		{
			$table->unique(array('sub_family_id', 'order'));
		});
	}

}
