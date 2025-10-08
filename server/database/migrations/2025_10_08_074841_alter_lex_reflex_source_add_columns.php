<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lex_reflex_source', function (Blueprint $table) {
            $table->string('page_number', 32);
            $table->mediumText('original_text');

            $table->dropForeign('lex_reflex_source_reflex_id_foreign');
            $table->dropForeign('lex_reflex_source_source_id_foreign');
            $table->dropUnique(['reflex_id','source_id']);
            $table->index(['reflex_id', 'source_id']);
            $table->foreign('reflex_id')->references('id')->on('lex_reflex')->onUpdate('RESTRICT')->onDelete('cascade');
            $table->foreign('source_id')->references('id')->on('lex_source')->onUpdate('RESTRICT')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('lex_reflex_source', function (Blueprint $table) {
            $table->dropColumn('page_number');
            $table->dropColumn('original_text');

            $table->dropIndex(['reflex_id','source_id']);
            $table->unique(['reflex_id', 'source_id']);
        });
    }
};
