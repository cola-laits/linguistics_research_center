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
        Schema::create('lex_lexicon_data_cache', function (Blueprint $table) {
            $table->uuid();
            $table->foreignId('lexicon_id')
                ->constrained(table: 'lex_lexicon')
                ->onDelete('cascade');
            $table->foreignId('reflex_id')
                ->constrained(table: 'lex_reflex')
                ->onDelete('cascade');
            $table->string('content_lang_code', 10);
            $table->json('data');
            $table->timestamps();

            $table->index(['lexicon_id', 'content_lang_code', 'reflex_id'], 'lexicon_id_lang_code_reflex_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lex_lexicon_data_cache');
    }
};
