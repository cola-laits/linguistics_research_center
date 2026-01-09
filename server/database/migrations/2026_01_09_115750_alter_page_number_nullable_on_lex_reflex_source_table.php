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
        Schema::table('lex_reflex_source', function (Blueprint $table) {
            $table->string('page_number', 32)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lex_reflex_source', function (Blueprint $table) {
            $table->string('page_number', 32)->nullable(false)->change();
        });
    }
};
