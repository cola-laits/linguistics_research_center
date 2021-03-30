<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLexReflexColumnEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lex_reflex', function(Blueprint $table) {
            $table->text('entries')->nullable();
        });

        $reflexes = \DB::table('lex_reflex')->get();
        foreach ($reflexes as $reflex) {
            $entries = \DB::table('lex_reflex_entry')
                ->where('reflex_id', $reflex->id)
                ->orderBy('order','ASC')
                ->pluck('entry')
                ->map(function($entry) {
                    return (object)['text'=>$entry];
                })->toJson();
            \DB::table('lex_reflex')
                ->where('id', $reflex->id)
                ->update(['entries' => $entries]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lex_reflex', function(Blueprint $table) {
            $table->dropColumn('entries');
        });
    }
}
