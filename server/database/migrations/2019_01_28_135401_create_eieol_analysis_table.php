<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEieolAnalysisTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('eieol_analysis', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('analysis', 191)->nullable()->index();
			$table->integer('language_id')->unsigned()->index('eieol_analysis_language_id_foreign');
			$table->timestamps();
			$table->string('created_by', 191)->nullable();
			$table->string('updated_by', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('eieol_analysis');
	}

}
