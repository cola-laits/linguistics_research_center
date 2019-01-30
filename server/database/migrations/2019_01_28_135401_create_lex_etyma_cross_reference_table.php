<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLexEtymaCrossReferenceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_etyma_cross_reference', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('from_etyma_id')->unsigned()->index('lex_etyma_cross_reference_from_etyma_id_foreign');
			$table->integer('to_etyma_id')->unsigned()->index('lex_etyma_cross_reference_to_etyma_id_foreign');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lex_etyma_cross_reference');
	}

}
