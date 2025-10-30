<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('link');
    }

    public function down(): void
    {
        Schema::create('link', function(Blueprint $table)
        {
            $table->integer('id', true);
            $table->integer('person')->nullable();
            $table->string('display', 250)->nullable();
            $table->string('url', 250)->nullable();
        });
    }
};
