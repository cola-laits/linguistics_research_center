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
        Schema::table('eieol_series', function(Blueprint $table) {
            $table->dropColumn('use_old_gloss_ui');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eieol_series', function(Blueprint $table) {
            $table->boolean('use_old_gloss_ui')->nullable()->default(1);
        });
    }
};
