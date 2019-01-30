<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLexetymasemfieldRemoveBys extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lex_etyma_semantic_field', function(Blueprint $table)
		{
			$table->dropColumn('created_by');
			$table->dropColumn('updated_by');
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
			$table->string('created_by');
			$table->string('updated_by');
		});
	}

}
