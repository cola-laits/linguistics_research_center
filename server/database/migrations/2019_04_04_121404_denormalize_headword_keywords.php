<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DenormalizeHeadwordKeywords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eieol_head_word', function(Blueprint $table) {
            $table->string('keywords',1024);
        });

        $data = DB::select('select head_word_id,group_concat(keyword) as kwcsv from eieol_head_word_keyword group by head_word_id');
        foreach ($data as $info) {
            DB::update('UPDATE eieol_head_word SET keywords=? WHERE id=?', [
                $info->kwcsv,
                $info->head_word_id
            ]);
        }

        Schema::drop('eieol_head_word_keyword');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
