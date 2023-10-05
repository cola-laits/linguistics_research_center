<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lex_lexicon', function (Blueprint $table) {
            $table->string('viewer_lang_options')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lex_lexicon', function (Blueprint $table) {
            $table->dropColumn('viewer_lang_options');
        });
    }
};
