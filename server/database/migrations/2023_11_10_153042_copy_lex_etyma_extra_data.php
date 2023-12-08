<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        DB::table("lex_etyma")->orderBy('id')->lazy()->each(function ($lex_etyma_row)  {
            DB::table("lex_etyma_extra_data")-> insert([
                    'etyma_id' => $lex_etyma_row -> id,
                    'key' => 'old_id',
                    'value' => json_encode(['en' => $lex_etyma_row -> old_id])
            ]);

            DB::table("lex_etyma_extra_data")-> insert([
                'etyma_id' => $lex_etyma_row -> id,
                'key' => 'page_number',
                'value' => json_encode(['en' => $lex_etyma_row -> page_number])
            ]);


        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        ## drop table where the id from the lex_etyman_extra_data matches with the id from the lex_etyma
        // DB::table("lex_etyma_extra_data") -> where::(id from lex_eyma extra data matches with id for lex_etyma table)
        DB::table('lex_etyma_extra_data')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('lex_etyma')
                    ->whereColumn('lex_etyma_extra_data.etyma_id', "=", 'lex_etyma.id');
            })
            ->delete();

    }
};
