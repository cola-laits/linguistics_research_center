<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EieolLesson;
use App\Models\EieolGloss;

/**
 * App\Models\EieolGlossedText
 *
 * @property int $id
 * @property int $lesson_id
 * @property string|null $glossed_text
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $audio_url
 * @property array|null $custom_gloss_mapping
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EieolGloss[] $glosses
 * @property-read int|null $glosses_count
 * @property-read \App\Models\EieolLesson $lesson
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGlossedText newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGlossedText newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGlossedText query()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGlossedText whereAudioUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGlossedText whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGlossedText whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGlossedText whereCustomGlossMapping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGlossedText whereGlossedText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGlossedText whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGlossedText whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGlossedText whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGlossedText whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGlossedText whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class EieolGlossedText extends Model
{

    protected $table = 'eieol_glossed_text';
    protected $casts = [
        'custom_gloss_mapping' => 'array'
    ];

    public function lesson() {
        return $this->belongsTo(EieolLesson::class);
    }

    public function glosses() {
        return $this->hasMany(EieolGloss::class, 'glossed_text_id')
            ->orderBy('order');
    }

    public function clickable_gloss_text() {

        if ($this->has_custom_gloss_mapping()) {
            return $this->apply_custom_gloss_mapping();
        }

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
            $replacement = '<a href="#" onclick="return false;" class="click_gloss" data-gloss-ids="[' . $id . ']">'.$text_matched. '</a>';
            $str = mb_substr($str,0,$posn) . $replacement . mb_substr($str,$posn+mb_strlen($form));
            $str_posn = $posn+mb_strlen($replacement);
        }

        return $str;

    }

    protected function has_custom_gloss_mapping() {
        if (!$this->custom_gloss_mapping) {
            return false;
        }
        foreach (array_values($this->custom_gloss_mapping) as $val) {
            if ($val) {
                return true;
            }
        }
        return false;
    }

    protected function apply_custom_gloss_mapping() {
        $result = "";
        $old_mapped_glosses = [];
        foreach (mb_str_split($this->glossed_text) as $ix=>$chr) {
            $current_mapped_glosses = [];
            foreach ($this->custom_gloss_mapping as $gloss_id=>$posns) {
                if (in_array($ix, $posns)) {
                    $current_mapped_glosses []= $gloss_id;
                }
            }
            if ($old_mapped_glosses != $current_mapped_glosses) {
                if ($old_mapped_glosses) {
                    $result .= "</a>";
                }
                if ($current_mapped_glosses) {
                    $result .= "<a href=\"#\" onclick=\"return false;\" class=\"click_gloss\" data-gloss-ids=\"" . json_encode($current_mapped_glosses) . "\">";
                }
            }
            $old_mapped_glosses = $current_mapped_glosses;
            $result .= $chr;
        }
        if ($old_mapped_glosses) {
            $result .= "</a>";
        }
        return $result;
    }

}
