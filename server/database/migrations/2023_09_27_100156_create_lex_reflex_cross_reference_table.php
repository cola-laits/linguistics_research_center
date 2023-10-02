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
        Schema::create('lex_reflex_cross_reference', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('from_reflex_id');
            $table->unsignedInteger('to_reflex_id');
            $table->text('relationship');
            $table->timestamps();

            $table->foreign('from_reflex_id')->references('id')->on('lex_reflex');
            $table->foreign('to_reflex_id')->references('id')->on('lex_reflex');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lex_reflex_cross_reference');
    }
};
