<?php

namespace App\Models;

use DateTimeInterface;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\IssueComment;
use Illuminate\Support\Carbon;

class Issue extends Model
{
    protected $table = 'issue';

    protected $guarded = ['id','created_at','updated_at'];

    protected function serializeDate(DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }

    public static function getTextFromPointer($pointer) {
        if (strpos($pointer,'/')===0) {
            $pointer = substr($pointer,1);
        }
        $pointer_parts = explode('/', $pointer);
        if ($pointer_parts[0] === 'lesson') {
            $lesson_id = $pointer_parts[1];
            if ($pointer_parts[2] === 'intro_text') {
                $lesson = EieolLesson::findOrFail($lesson_id);
                return $lesson->intro_text;
            }
            if ($pointer_parts[2] === 'lesson_translation') {
                $lesson = EieolLesson::findOrFail($lesson_id);
                return $lesson->lesson_translation;
            }
            if ($pointer_parts[2] === 'grammar') {
                $grammar = EieolGrammar::findOrFail($pointer_parts[3]);
                return $grammar->grammar_text;
            }
            if ($pointer_parts[2] === 'gloss') {
                $gloss = EieolGloss::findOrFail($pointer_parts[3]);
                return <<<EOT
<b>Surface Form:</b><br>
$gloss->surface_form<br>
<b>Contextual Gloss:</b><br>
$gloss->contextual_gloss<br>
<b>Underlying Form:</b><br>
$gloss->underlying_form
EOT;
            }
            if ($pointer_parts[2] === 'glossed_text') {
                $glossed_text = EieolGlossedText::findOrFail($pointer_parts[3]);
                return $glossed_text->glossed_text;
            }
        }

        return 'Unknown';
    }

    public static function getPointerDescFromPointer($pointer) {
        if (strpos($pointer,'/')===0) {
            $pointer = substr($pointer,1);
        }
        $pointer_parts = explode('/', $pointer);
        if ($pointer_parts[0] === 'lesson') {
            $lesson_id = $pointer_parts[1];
            $lesson = EieolLesson::findOrFail($lesson_id);
            $series = $lesson->series;
            if ($pointer_parts[2] === 'intro_text') {
                return 'Series \''.$series->title.'\', Intro Text, Lesson '.$lesson->order.': '.$lesson->title;
            }
            if ($pointer_parts[2] === 'lesson_translation') {
                return 'Series \''.$series->title.'\', Translation, Lesson '.$lesson->order.': '.$lesson->title;
            }
            if ($pointer_parts[2] === 'grammar') {
                $grammar = EieolGrammar::findOrFail($pointer_parts[3]);
                return 'Series \''.$series->title.'\', Grammar #'.$grammar->section_number.', Lesson '.$lesson->order.': '.$lesson->title;
            }
            if ($pointer_parts[2] === 'gloss') {
                $gloss = EieolGloss::findOrFail($pointer_parts[3]);
                $glossed_text = $gloss->glossed_text;
                return 'Series \''.$series->title.'\', Glossed Text #'.$glossed_text->order.', Gloss '.$gloss->order.', Lesson '.$lesson->order.': '.$lesson->title;
            }
            if ($pointer_parts[2] === 'glossed_text') {
                $glossed_text = EieolGlossedText::findOrFail($pointer_parts[3]);
                return 'Series \''.$series->title.'\', Glossed Text #'.$glossed_text->order.', Lesson '.$lesson->order.': '.$lesson->title;
            }
        }

        return 'Unknown';
    }

    public function comments() {
        return $this->hasMany(IssueComment::class);
    }
}
