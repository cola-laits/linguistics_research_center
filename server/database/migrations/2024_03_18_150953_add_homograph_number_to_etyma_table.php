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
        Schema::table('lex_etyma', function (Blueprint $table) {
            $table->string('homograph_number', 16)->nullable()->after('entry');
        });

        DB::table('lex_etyma')->orderBy('id')->chunk(100, function ($etyma) {
            foreach ($etyma as $etymon) {
                $etymon->homograph_number = preg_match('/^\d+[a-z]?\.+/', $etymon->entry, $matches) ? $matches[0] : null;
                if ($etymon->homograph_number === null) {
                    continue;
                }
                $etymon->homograph_number = preg_replace('/\.$/', '', $etymon->homograph_number);
                $etymon->entry = trim(preg_replace('/^\d+[a-z]?\.+/', '', $etymon->entry));
                DB::update('UPDATE lex_etyma SET entry = ?, homograph_number = ? WHERE id = ?', [$etymon->entry, $etymon->homograph_number, $etymon->id]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lex_etyma', function (Blueprint $table) {
            $table->dropColumn('homograph_number');
        });
    }
};
