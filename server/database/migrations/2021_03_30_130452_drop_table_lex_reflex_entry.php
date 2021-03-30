<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTableLexReflexEntry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('lex_reflex_entry');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // none - restore from backup
    }
}
