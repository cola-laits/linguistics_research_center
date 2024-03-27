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
        Schema::create('block_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('block_page_id');
            $table->foreign('block_page_id')->references('id')->on('block_pages')->onDelete('cascade');
            $table->morphs('blockable');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('block_texts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('eieol_lesson_id');
            $table->timestamps();
        });

        Schema::create('block_glossed_texts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('eieol_glossed_text_id');
            $table->timestamps();
        });

        Schema::create('block_text_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('eieol_lesson_id');
            $table->timestamps();
        });

        Schema::create('block_grammars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('eieol_grammar_id');
            $table->timestamps();
        });

        Schema::table('eieol_lesson', function (Blueprint $table) {
            $table->unsignedBigInteger('block_page_id')->nullable();
            $table->foreign('block_page_id')->references('id')->on('block_pages')->onDelete('set null');
        });

        $lessons = DB::table('eieol_lesson')->get();
        foreach ($lessons as $lesson) {
            $block_order = 0;
            $blockPage = DB::table('block_pages')->insertGetId([
                'title' => $lesson->title,
                'created_at' => $lesson->created_at,
                'updated_at' => $lesson->updated_at,
            ]);
            DB::table('eieol_lesson')->where('id', $lesson->id)->update(['block_page_id' => $blockPage]);

            $block = DB::table('blocks')->insertGetId([
                'order' => $block_order++,
                'block_page_id' => $blockPage,
                'blockable_type' => 'App\Models\BlockText',
                'blockable_id' => DB::table('block_texts')->insertGetId([
                    'eieol_lesson_id' => $lesson->id,
                    'created_at' => $lesson->created_at,
                    'updated_at' => $lesson->updated_at,
                ]),
                'created_at' => $lesson->created_at,
                'updated_at' => $lesson->updated_at,
            ]);

            // for each eieol_glossed_text in the lesson, create a block too
            $glossedTexts = DB::table('eieol_glossed_text')->where('lesson_id', $lesson->id)->get();
            foreach ($glossedTexts as $glossedText) {
                $block = DB::table('blocks')->insertGetId([
                    'order' => $block_order++,
                    'block_page_id' => $blockPage,
                    'blockable_type' => 'App\Models\BlockGlossedText',
                    'blockable_id' => DB::table('block_glossed_texts')->insertGetId([
                        'eieol_glossed_text_id' => $glossedText->id,
                        'created_at' => $glossedText->created_at,
                        'updated_at' => $glossedText->updated_at,
                    ]),
                    'created_at' => $glossedText->created_at,
                    'updated_at' => $glossedText->updated_at,
                ]);
            }

            $block = DB::table('blocks')->insertGetId([
                'order' => $block_order++,
                'block_page_id' => $blockPage,
                'blockable_type' => 'App\Models\BlockTextTranslation',
                'blockable_id' => DB::table('block_text_translations')->insertGetId([
                    'eieol_lesson_id' => $lesson->id,
                    'created_at' => $lesson->created_at,
                    'updated_at' => $lesson->updated_at,
                ]),
                'created_at' => $lesson->created_at,
                'updated_at' => $lesson->updated_at,
            ]);

            // for each eieol_grammar in the lesson, create a block too
            $grammars = DB::table('eieol_grammar')->where('lesson_id', $lesson->id)->get();
            foreach ($grammars as $grammar) {
                $block = DB::table('blocks')->insertGetId([
                    'order' => $block_order++,
                    'block_page_id' => $blockPage,
                    'blockable_type' => 'App\Models\BlockGrammar',
                    'blockable_id' => DB::table('block_grammars')->insertGetId([
                        'eieol_grammar_id' => $grammar->id,
                        'created_at' => $grammar->created_at,
                        'updated_at' => $grammar->updated_at,
                    ]),
                    'created_at' => $grammar->created_at,
                    'updated_at' => $grammar->updated_at,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eieol_lesson', function (Blueprint $table) {
            $table->dropForeign(['block_page_id']);
            $table->dropColumn('block_page_id');
        });

        Schema::dropIfExists('block_grammars');
        Schema::dropIfExists('block_text_translations');
        Schema::dropIfExists('block_glossed_texts');
        Schema::dropIfExists('block_texts');
        Schema::dropIfExists('blocks');
        Schema::dropIfExists('block_pages');
    }
};
