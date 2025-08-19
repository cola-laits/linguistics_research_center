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
        Schema::table('eieol_series', function (Blueprint $table) {
            $table->dropColumn('meta_tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eieol_series', function (Blueprint $table) {
            $table->text('meta_tags')->nullable();
        });
    }
};
