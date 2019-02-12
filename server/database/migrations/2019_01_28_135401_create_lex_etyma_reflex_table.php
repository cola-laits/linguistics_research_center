<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLexEtymaReflexTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lex_etyma_reflex', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('etyma_id')->unsigned()->index('lex_etyma_reflex_etyma_id_foreign');
			$table->integer('reflex_id')->unsigned()->index('lex_etyma_reflex_reflex_id_foreign');
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
		Schema::drop('lex_etyma_reflex');
	}

}
