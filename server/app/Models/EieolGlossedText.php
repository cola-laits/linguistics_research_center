<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\EieolLesson;
use App\Models\EieolGloss;
use Illuminate\Support\Carbon;

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
