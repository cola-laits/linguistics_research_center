<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RenormalizeUnicode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Removed to command NormalizeUnicodeText
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // no back-migration
    }
}
