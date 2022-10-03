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
        Schema::table('lex_etyma', function(Blueprint $table) {
            $table->addColumn('mediumtext', 'extra_data')->nullable();
        });
        Schema::table('lex_reflex', function(Blueprint $table) {
            $table->addColumn('mediumtext', 'extra_data')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lex_etyma', function(Blueprint $table) {
            $table->dropColumn('extra_data');
        });
        Schema::table('lex_reflex', function(Blueprint $table) {
            $table->dropColumn('extra_data');
        });
    }
};
