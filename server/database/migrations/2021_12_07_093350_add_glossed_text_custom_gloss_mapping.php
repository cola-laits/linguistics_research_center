<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGlossedTextCustomGlossMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eieol_glossed_text', function (Blueprint $table) {
            $table->mediumText('custom_gloss_mapping')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eieol_glossed_text', function (Blueprint $table) {
            $table->dropColumn('custom_gloss_mapping');
        });
    }
}
