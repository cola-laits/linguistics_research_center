<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lex_lexicon', function(Blueprint $table) {
            $table->addColumn('text', 'description')->nullable();
        });
        Schema::table('lex_language', function(Blueprint $table) {
            $table->addColumn('text', 'description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lex_lexicon', function(Blueprint $table) {
            $table->dropColumn('description');
        });
        Schema::table('lex_language', function(Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
