<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EieolGlossedText
 *
 * @property int $id
 * @property int $lesson_id
 * @property string|null $glossed_text
 * @property int $order
 * @property string|null $author_comments
 * @property int|null $author_done
 * @property string|null $admin_comments
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EieolGloss[] $glosses
 * @property-read \App\EieolLesson $lesson
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedText newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedText newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedText query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedText whereAdminComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedText whereAuthorComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedText whereAuthorDone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedText whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedText whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedText whereGlossedText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedText whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedText whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedText whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedText whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedText whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property string|null $audio_url
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGlossedText whereAudioUrl($value)
 */
class EieolGlossedText extends Model
{

    protected $table = 'eieol_glossed_text';

    public function lesson() {
        return $this->belongsTo('\App\EieolLesson');
    }

    public function glosses() {
        return $this->hasMany('\App\EieolGloss', 'glossed_text_id')
            ->orderBy('order');
    }

    public function clickable_gloss_text() {

        //this makes a new version of the glossed text with span tags for each gloss.
        //Then you can make them clickable so they toggle the gloss.

        $text = $this->glossed_text;

        $text = str_replace("\r", " ", $text);
        $text = str_replace("\n", " ", $text);
        $text = str_replace("<br/>", " <br/> ", $text);
        $text = str_replace("<br />", " <br /> ", $text);
        $text = str_replace("<br>", " <br> ", $text);
        $text = str_replace("<p>", " <p> ", $text);
        $text = str_replace("</p>", " </p> ", $text);

        $clickable_text = $this->makeClickable($text, "surface_form");
        $clickable_text = $this->makeClickable($clickable_text, "underlying_form");

        return $clickable_text;

    }

    private function makeClickable($str, $f) {

        $str_posn = 0;

        foreach ($this->glosses as $g) {
            $form = $g->$f;
            $id = $g->id;
            if (!($form && $id)) {
                continue;
            }

            $posn = mb_stripos($str, $form, $str_posn);
            if ($posn === FALSE) {
                continue;
            }
            $text_matched = mb_substr($str, $posn, mb_strlen($form));
            $replacement = '<a href="#" onclick="return false;" class="click_gloss" id="pivot_' . $id . '">'.$text_matched. '</a>';
            $str = mb_substr($str,0,$posn) . $replacement . mb_substr($str,$posn+mb_strlen($form));
            $str_posn = $posn+mb_strlen($replacement);
        }

        return $str;

    }

}
