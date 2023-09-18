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
        Schema::table('lex_semantic_field', function (Blueprint $table) {
            $table->dropUnique(['number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lex_semantic_field', function (Blueprint $table) {
            $table->unique(['number']);
        });
    }
};
