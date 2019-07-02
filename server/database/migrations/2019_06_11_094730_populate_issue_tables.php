<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PopulateIssueTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // convert lesson intro_text comments
        $lessons = DB::select('select * from eieol_lesson where author_comments!=\'\' or admin_comments!=\'\'');
        foreach ($lessons as $lesson) {
            $series = DB::select('select * from eieol_series where id = '.$lesson->series_id)[0];

            $now = new DateTime();
            $admin = nl2br(htmlspecialchars($lesson->admin_comments));
            $author = nl2br(htmlspecialchars($lesson->author_comments));
            $comment = <<<EOT
<b>Admin comments:</b><br>
$admin<br>
<br>
<b>Author Comments:</b><br>
$author
EOT;
            $issue_id = DB::table('issue')->insertGetId([
                'name'=>'Series \''.$series->title.'\', Intro Text, Lesson '.$lesson->order.': '.$lesson->title,
                'text'=>$lesson->intro_text,
                'pointer'=>'/lesson/'.$lesson->id.'/intro_text',
                'pointer_desc'=>'Series \''.$series->title.'\', Intro Text, Lesson '.$lesson->order.': '.$lesson->title,
                'status'=>$lesson->author_done ? 'closed' : 'open',
                'created_at'=>$now,
                'updated_at'=>$now
            ]);
            DB::table('issue_comment')->insert([
                'issue_id'=>$issue_id,
                'type'=>'created',
                'text'=>$comment,
                'user_logon'=>'system',
                'created_at'=>$now,
                'updated_at'=>$now
            ]);
        }

        // convert lesson translation comments
        $lessons = DB::select('select * from eieol_lesson where translation_author_comments!=\'\' or translation_admin_comments!=\'\'');
        foreach ($lessons as $lesson) {
            $series = DB::select('select * from eieol_series where id = '.$lesson->series_id)[0];

            $now = new DateTime();
            $admin = nl2br(htmlspecialchars($lesson->translation_admin_comments));
            $author = nl2br(htmlspecialchars($lesson->translation_author_comments));
            $comment = <<<EOT
<b>Admin comments:</b><br>
$admin<br>
<br>
<b>Author Comments:</b><br>
$author
EOT;
            $issue_id = DB::table('issue')->insertGetId([
                'name'=>'Series \''.$series->title.'\', Translation, Lesson '.$lesson->order.': '.$lesson->title,
                'text'=>$lesson->lesson_translation,
                'pointer'=>'/lesson/'.$lesson->id.'/lesson_translation',
                'pointer_desc'=>'Series \''.$series->title.'\', Translation, Lesson '.$lesson->order.': '.$lesson->title,
                'status'=>$lesson->translation_author_done ? 'closed' : 'open',
                'created_at'=>$now,
                'updated_at'=>$now
            ]);
            DB::table('issue_comment')->insert([
                'issue_id'=>$issue_id,
                'type'=>'created',
                'text'=>$comment,
                'user_logon'=>'system',
                'created_at'=>$now,
                'updated_at'=>$now
            ]);
        }

        // convert lesson grammar comments
        $grammars = DB::select('select * from eieol_grammar where author_comments!=\'\' or admin_comments!=\'\'');
        foreach ($grammars as $grammar) {
            $lesson = DB::select('select * from eieol_lesson where id = '.$grammar->lesson_id)[0];
            $series = DB::select('select * from eieol_series where id = '.$lesson->series_id)[0];

            $now = new DateTime();
            $admin = nl2br(htmlspecialchars($grammar->admin_comments));
            $author = nl2br(htmlspecialchars($grammar->author_comments));
            $comment = <<<EOT
<b>Admin comments:</b><br>
$admin<br>
<br>
<b>Author Comments:</b><br>
$author
EOT;
            $issue_id = DB::table('issue')->insertGetId([
                'name'=>'Series \''.$series->title.'\', Grammar #'.$grammar->section_number.', Lesson '.$lesson->order.': '.$lesson->title,
                'text'=>$grammar->grammar_text,
                'pointer'=>'/lesson/'.$lesson->id.'/grammar/'.$grammar->id,
                'pointer_desc'=>'Series \''.$series->title.'\', Grammar #'.$grammar->section_number.', Lesson '.$lesson->order.': '.$lesson->title,
                'status'=>$grammar->author_done ? 'closed' : 'open',
                'created_at'=>$now,
                'updated_at'=>$now
            ]);
            DB::table('issue_comment')->insert([
                'issue_id'=>$issue_id,
                'type'=>'created',
                'text'=>$comment,
                'user_logon'=>'system',
                'created_at'=>$now,
                'updated_at'=>$now
            ]);
        }

        // convert lesson glossed_text comments
        $glossed_texts = DB::select('select * from eieol_glossed_text where author_comments!=\'\' or admin_comments!=\'\'');
        foreach ($glossed_texts as $glossed_text) {
            $lesson = DB::select('select * from eieol_lesson where id = '.$glossed_text->lesson_id)[0];
            $series = DB::select('select * from eieol_series where id = '.$lesson->series_id)[0];

            $now = new DateTime();
            $admin = nl2br(htmlspecialchars($glossed_text->admin_comments));
            $author = nl2br(htmlspecialchars($glossed_text->author_comments));
            $comment = <<<EOT
<b>Admin comments:</b><br>
$admin<br>
<br>
<b>Author Comments:</b><br>
$author
EOT;
            $issue_id = DB::table('issue')->insertGetId([
                'name'=>'Series \''.$series->title.'\', Glossed Text #'.$glossed_text->order.', Lesson '.$lesson->order.': '.$lesson->title,
                'text'=>$glossed_text->glossed_text,
                'pointer'=>'/lesson/'.$lesson->id.'/glossed_text/'.$glossed_text->id,
                'pointer_desc'=>'Series \''.$series->title.'\', Glossed Text #'.$glossed_text->order.', Lesson '.$lesson->order.': '.$lesson->title,
                'status'=>$glossed_text->author_done ? 'closed' : 'open',
                'created_at'=>$now,
                'updated_at'=>$now
            ]);
            DB::table('issue_comment')->insert([
                'issue_id'=>$issue_id,
                'type'=>'created',
                'text'=>$comment,
                'user_logon'=>'system',
                'created_at'=>$now,
                'updated_at'=>$now
            ]);
        }

        // convert lesson glossed_text comments
        $glosses = DB::select('select * from eieol_gloss where author_comments!=\'\' or admin_comments!=\'\'');
        foreach ($glosses as $gloss) {
            if (!$gloss->glossed_text_id) { continue; }
            $glossed_text = DB::select('select * from eieol_glossed_text where id = '.$gloss->glossed_text_id)[0];
            $lesson = DB::select('select * from eieol_lesson where id = '.$glossed_text->lesson_id)[0];
            $series = DB::select('select * from eieol_series where id = '.$lesson->series_id)[0];

            $now = new DateTime();
            $admin = nl2br(htmlspecialchars($gloss->admin_comments));
            $author = nl2br(htmlspecialchars($gloss->author_comments));
            $comment = <<<EOT
<b>Admin comments:</b><br>
$admin<br>
<br>
<b>Author Comments:</b><br>
$author
EOT;
            $issue_text = <<<EOT
<b>Surface Form:</b><br>
$gloss->surface_form<br>
<b>Contextual Gloss:</b><br>
$gloss->contextual_gloss<br>
<b>Underlying Form:</b><br>
$gloss->underlying_form
EOT;

            $issue_id = DB::table('issue')->insertGetId([
                'name'=>'Series \''.$series->title.'\', Glossed Text #'.$glossed_text->order.', Gloss '.$gloss->order.', Lesson '.$lesson->order.': '.$lesson->title,
                'text'=>$issue_text,
                'pointer'=>'/lesson/'.$lesson->id.'/gloss/'.$gloss->id,
                'pointer_desc'=>'Series \''.$series->title.'\', Glossed Text #'.$glossed_text->order.', Gloss '.$gloss->order.', Lesson '.$lesson->order.': '.$lesson->title,
                'status'=>$gloss->author_done ? 'closed' : 'open',
                'created_at'=>$now,
                'updated_at'=>$now
            ]);
            DB::table('issue_comment')->insert([
                'issue_id'=>$issue_id,
                'type'=>'created',
                'text'=>$comment,
                'user_logon'=>'system',
                'created_at'=>$now,
                'updated_at'=>$now
            ]);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete('delete from issue_comment');
        DB::delete('delete from issue');
    }
}
