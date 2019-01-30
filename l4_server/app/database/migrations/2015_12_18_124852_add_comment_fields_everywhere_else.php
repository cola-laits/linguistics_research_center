<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentFieldsEverywhereElse extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eieol_grammar', function(Blueprint $table)
		{
			$table->longText('author_comments')->after('section_number')->nullable();
			$table->boolean('author_done')->after('author_comments')->nullable();
			$table->longText('admin_comments')->after('author_done')->nullable();
		});
		
		Schema::table('eieol_glossed_text', function(Blueprint $table)
		{
			$table->longText('author_comments')->after('order')->nullable();
			$table->boolean('author_done')->after('author_comments')->nullable();
			$table->longText('admin_comments')->after('author_done')->nullable();
		});
		
		Schema::table('eieol_gloss', function(Blueprint $table)
		{
			$table->longText('author_comments')->after('language_id')->nullable();
			$table->boolean('author_done')->after('author_comments')->nullable();
			$table->longText('admin_comments')->after('author_done')->nullable();
		});
		
		Schema::table('eieol_lesson', function(Blueprint $table)
		{
			$table->longText('translation_author_comments')->after('admin_comments')->nullable();
			$table->boolean('translation_author_done')->after('translation_author_comments')->nullable();
			$table->longText('translation_admin_comments')->after('translation_author_done')->nullable();
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
			$table->dropColumn('author_comments');
			$table->dropColumn('author_done');
			$table->dropColumn('admin_comments');
		});
		
		Schema::table('eieol_glossed_text', function(Blueprint $table)
		{
			$table->dropColumn('author_comments');
			$table->dropColumn('author_done');
			$table->dropColumn('admin_comments');
		});
		
		Schema::table('eieol_gloss', function(Blueprint $table)
		{
			$table->dropColumn('author_comments');
			$table->dropColumn('author_done');
			$table->dropColumn('admin_comments');
		});
		
		Schema::table('eieol_lesson', function(Blueprint $table)
		{
			$table->dropColumn('translation_author_comments');
			$table->dropColumn('translation_author_done');
			$table->dropColumn('translation_admin_comments');
		});
		
		
	}

}
