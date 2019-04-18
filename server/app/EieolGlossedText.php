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

        $text = $this->removeFormatting($this->glossed_text);

        $text = str_replace("\r", " ", $text);
        $text = str_replace("\n", " ", $text);
        $text = str_replace("<br/>", " <br/> ", $text);
        $text = str_replace("<br />", " <br /> ", $text);
        $text = str_replace("<br>", " <br> ", $text);
        $text = str_replace("<p>", " <p> ", $text);
        $text = str_replace("</p>", " </p> ", $text);
        //$text = str_replace("</sup>", "</sup> ", $text);

        $clickable_text = $this->makeClickable($text, "surface_form");
        $clickable_text = $this->makeClickable($clickable_text, "underlying_form");

        return $clickable_text;

    }

    private function makeClickable($str, $f) {

        $glosses = [];
        foreach ($this->glosses as $g) {
            $gloss['form'] = $g->$f;
            $gloss['id'] = $g->id;
            if ($gloss['form'] && $gloss['id']) $glosses[] = $gloss;
        }

        //order from longest to short for greedy matching!!
        usort($glosses,
            function ($a, $b) {
                return strlen($a['form']) < strlen($b['form']);
            });

        // There's no regex-based, multibyte string tokenizer in PHP.  So we gotta write one.
        $str_parts = [];
        // Regex has a character class for word boundaries that we want to tweak a little.
        // For us, a word character is a single quote (as in "Margaret's")
        //         or a hyphen (as in "rain-soaked")
        //         or an Armenian question mark (՞), which occurs in the middle of words rather than at the end
        //         or a < or > (since there's still a lot of HTML garbage in our gloss text)
        //         or anything Unicode considers a letter (category L)
        //         or anything Unicode considers a (non-punctuation) 'mark' (category M)
        // A word boundary is anything that's not a word character.
        $word_bounds = "[^'\-՞<>\p{L}\p{M}]";
        // walk through the string, looking for word boundaries.  Word boundaries may be one byte long or may be more.
        mb_ereg_search_init($str, $word_bounds);
        // Keep track of the position and length of the old word boundary match.
        $old_posn = 0;
        $old_len = 0;
        while (true) {
            // Do a search for word boundaries, noting the position and length of the match.
            $search_info = mb_ereg_search_pos();
            if ($search_info===false) {
                if ($old_len>0) {
                    $str_parts [] = substr($str, $old_posn - $old_len, $old_len);
                }
                $str_parts []= substr($str, $old_posn);
                break;
            }
            [$posn, $len] = $search_info;
            // update the internal pointer past this match, so that future searches skip it.
            mb_ereg_search_setpos($posn+$len);
            // add the matched word boundary, and the word characters between the old boundary and this one, to a list.
            if ($old_len>0) {
                $str_parts [] = substr($str, $old_posn - $old_len, $old_len);
            }
            $str_parts []= substr($str, $old_posn, $posn-$old_posn);
            $old_posn = $posn+$len;
            $old_len = $len;
        }

        // At this point, we should have split the string into an array of tokens,
        // Some, we want to surround with <a></a> tags.  We only wanna do that once,
        // so mark which ones we've already processed.
        $replaced = [];
        foreach ($str_parts as $p) {
            $replaced []= false;
        }

        foreach ($glosses as $gloss) {
            $form = $this->removeFormatting($gloss['form']);

            $words = explode(" ", trim($form));

            foreach ($words as $word) {
                foreach ($str_parts as $i=>$part) {
                    if ($replaced[$i]) {
                        continue;
                    }
                    if ($this->mb_stricmp($part, $word)) {
                        $replaced[$i] = true;
                        $str_parts[$i] = '<a href="#" onclick="return false;" class="click_gloss" id="pivot_' . $gloss['id'] . '">'.$part."</a>";
                        break;
                    }
                }
            }

        }

        return implode("", $str_parts);

    }

    // multibyte case-insensitive string comparison
    // FIXME can I use mb_ereg_match instead of this?
    private function mb_stricmp($s1, $s2) {
        return mb_strlen($s1)===mb_strlen($s2) && mb_stripos($s1,$s2)===0;
    }

    private function removeFormatting($str) {

        $str = preg_replace('/(<font[^>]*>)|(<\/font>)/', '', $str);
        // $str = str_replace('<sup>', '', $str);
        // $str = str_replace('</sup>', '', $str);

        return $str;

    }

}
