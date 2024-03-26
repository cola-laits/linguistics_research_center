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
        Schema::table('eieol_lesson', function (Blueprint $table) {
            $table->longText('lesson_text')->nullable()->after('intro_text');
        });

        $lessons = DB::table('eieol_lesson')->get();
        foreach ($lessons as $lesson) {
            $lessonText = '';
            $glossedTexts = DB::table('eieol_glossed_text')->where('lesson_id', $lesson->id)->orderBy('order')->get();
            foreach ($glossedTexts as $glossedText) {
                $lessonText .= $glossedText->glossed_text . ' ';
            }
            DB::table('eieol_lesson')->where('id', $lesson->id)->update(['lesson_text' => $lessonText]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eieol_lesson', function (Blueprint $table) {
            $table->dropColumn('lesson_text');
        });
    }
};
