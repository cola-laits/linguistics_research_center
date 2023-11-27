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
        Schema::table('lex_language', function (Blueprint $table) {
            $table->dropColumn('custom_sort');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lex_language', function (Blueprint $table) {
            $table->text('custom_sort')->nullable();
        });
    }
};
