<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeHeadWordCollation extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE eieol_head_word convert to character set utf8 collate utf8_bin;');
		Schema::table('eieol_head_word', function(Blueprint $table) {
			$table->dropUnique('eieol_head_word_word_definition_unique');
			$table->unique(array('language_id', 'word', 'definition'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE eieol_head_word convert to character set utf8 collate utf8_unicode_ci;');
		Schema::table('eieol_head_word', function(Blueprint $table)
		{
			$table->dropUnique('eieol_head_word_language_id_word_definition_unique');
			$table->unique(array('word', 'definition'));
		});
	}

}
