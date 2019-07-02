<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropCommentColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $this->dropColumnIfExists('eieol_gloss', 'author_comments');
        $this->dropColumnIfExists('eieol_gloss', 'author_done');
        $this->dropColumnIfExists('eieol_gloss', 'admin_comments');

        $this->dropColumnIfExists('eieol_glossed_text', 'author_comments');
        $this->dropColumnIfExists('eieol_glossed_text', 'author_done');
        $this->dropColumnIfExists('eieol_glossed_text', 'admin_comments');

        $this->dropColumnIfExists('eieol_grammar', 'author_comments');
        $this->dropColumnIfExists('eieol_grammar', 'author_done');
        $this->dropColumnIfExists('eieol_grammar', 'admin_comments');

        $this->dropColumnIfExists('eieol_lesson', 'author_comments');
        $this->dropColumnIfExists('eieol_lesson', 'author_done');
        $this->dropColumnIfExists('eieol_lesson', 'admin_comments');
        $this->dropColumnIfExists('eieol_lesson', 'translation_author_comments');
        $this->dropColumnIfExists('eieol_lesson', 'translation_author_done');
        $this->dropColumnIfExists('eieol_lesson', 'translation_admin_comments');
    }

    protected function dropColumnIfExists($table, $column) {
        if (Schema::hasColumn($table, $column)) {
            Schema::table($table, function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('eieol_gloss', function (Blueprint $table) {
            $table->text('author_comments')->nullable();
            $table->boolean('author_done')->nullable();
            $table->text('admin_comments')->nullable();
        });

        Schema::table('eieol_glossed_text', function (Blueprint $table) {
            $table->text('author_comments')->nullable();
            $table->boolean('author_done')->nullable();
            $table->text('admin_comments')->nullable();
        });

        Schema::table('eieol_grammar', function (Blueprint $table) {
            $table->text('author_comments')->nullable();
            $table->boolean('author_done')->nullable();
            $table->text('admin_comments')->nullable();
        });

        Schema::table('eieol_gloss', function (Blueprint $table) {
            $table->text('author_comments')->nullable();
            $table->boolean('author_done')->nullable();
            $table->text('admin_comments')->nullable();
        });

        Schema::table('eieol_lesson', function (Blueprint $table) {
            $table->text('author_comments')->nullable();
            $table->boolean('author_done')->nullable();
            $table->text('admin_comments')->nullable();

            $table->text('translation_author_comments')->nullable();
            $table->boolean('translation_author_done')->nullable();
            $table->text('translation_admin_comments')->nullable();
        });
    }
}
