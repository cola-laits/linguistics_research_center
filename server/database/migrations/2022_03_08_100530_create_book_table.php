<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->integer('order');
            $table->text('short_description');
            $table->timestamps();
        });

        Schema::create('book_section', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->string('name');
            $table->string('slug');
            $table->integer('order');
            $table->longText('content');
            $table->timestamps();

            $table->unique(['book_id', 'slug']);
            $table->foreign('book_id')->references('id')->on('book');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_section');
        Schema::dropIfExists('book');
    }
};
