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
            $table->json('landing_page_content')->nullable();
            $table->json('protolanguage_page_content')->nullable();
            $table->dropColumn('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lex_lexicon', function (Blueprint $table) {
            $table->json('description')->nullable();
            $table->dropColumn('protolanguage_page_content');
            $table->dropColumn('landing_page_content');
        });
    }
};
