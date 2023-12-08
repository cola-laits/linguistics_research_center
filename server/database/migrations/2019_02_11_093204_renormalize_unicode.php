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

        DB::table($table)->orderBy('id')->lazy()->each(function ($row) use ($table, $column) {
            DB::table($table)
                ->where('id', $row->id)
                ->update([
                    'name' => 'thing'
                ]);
        });
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
