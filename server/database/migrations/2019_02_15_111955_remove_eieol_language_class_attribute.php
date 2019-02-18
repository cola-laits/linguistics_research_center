<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveEieolLanguageClassAttribute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eieol_language', function(Blueprint $table)
        {
            $table->dropColumn('class_attribute');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eieol_language', function(Blueprint $table)
        {
            $table->string('class_attribute', 191)->nullable();
        });
    }
}
