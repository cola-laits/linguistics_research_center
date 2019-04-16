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

        foreach ($glosses as $gloss) {

            $form = $this->removeFormatting($gloss['form']);

            // spaces between html tag attributes don't delineate words - tokenize them
            /* $tokenized = preg_replace("/<.*?(\s).*?>/","@@@",$form);*/
            // $tokenized = str_replace(" size=","@@@size=",$form);

            $words = explode(" ", trim($form));

            foreach ($words as $word) {
                // $word = str_replace("@@@"," ",$word); // de-tokenize
                $str = $this->attachClicks($str, $word, $gloss['id']);
                $str = $this->attachClicks($str, ucfirst($word), $gloss['id'], True);
            }

        }

        return $str;

    }

    private function attachClicks($str, $g, $id, $i = False) {

        $a = '<a href="#" onclick="return false;" class="click_gloss" id="pivot_' . $id . '">';

        $str = $this->mbReplace(' ' . $g . ' ', ' ' . $a . $g . '</a> ', $str);

        if ($this->startsWith($str, $g)) {
            $str = $a . mb_substr($str, 0, mb_strlen($g)) . '</a>' . mb_substr($str, mb_strlen($g));
        }

        if ($this->endsWith($str, $g)) {
            $str = mb_substr($str, 0, mb_strlen($str) - mb_strlen($g)) . $a . $g . '</a>';
        }

        // Special-case handling - see issue #51
        $str = $this->mbReplace('(' . $g . ')', '(' . $a . $g . '</a>' . ') ', $str);

        $punctuation = array(",", ".", "!", "?", ":", "(", ")", "։", "՝", "յ", "`", '"', ";", "·", "̃", "[", "]");

        foreach ($punctuation as $p) {

            $str = $this->mbReplace(' ' . $g . $p, ' ' . $a . $g . '</a>' . $p . ' ', $str);
            $str = $this->mbReplace($p . $g . ' ', $p . $a . $g . '</a> ', $str);

            if ($this->startsWith($str, $p . $g)) {
                $str = $p . $a . mb_substr($str, 1, mb_strlen($g)) . '</a>' . mb_substr($str, mb_strlen($g) + 1);
            }

            if ($this->startsWith($str, $g . $p)) {
                $str = $a . mb_substr($str, 0, mb_strlen($g)) . '</a>' . $p . mb_substr($str, mb_strlen($g) + 1);
            }

            if ($this->endsWith($str, $g . $p)) {
                $str = mb_substr($str, 0, mb_strlen($str) - 1 - mb_strlen($g)) . $a . $g . '</a>' . $p;
            }

        }

        // hacks for russian
        $str = $this->mbReplace(',' . $g . '.', ',' . $a . $g . '</a>.', $str);
        $str = $this->mbReplace('.' . $g . '.', '.' . $a . $g . '</a>.', $str);

        if ($i) $str = $this->mbiReplace(' ' . $g . ' ', ' ' . $a . $g . '</a> ', $str);

        return $str;
    }

    private function startsWith($haystack, $needle) {
        preg_match('/' . preg_quote($needle . ' ', '/') . '/ui', $haystack, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches as $m) {
            if ($m[1] == 0) return true;
        }

        return false;
    }

    private function endsWith($haystack, $needle) {
        $length = mb_strlen(' ' . $needle);

        return $length === 0 ||
            (mb_substr($haystack, -$length) === ' ' . $needle);
    }

    private function mbReplace($search, $replace, $subject, $encoding = 'UTF-8', &$count = 0) {

        $searches = is_array($search) ? array_values($search) : [$search];
        $replacements = is_array($replace) ? array_values($replace) : [$replace];
        $replacements = array_pad($replacements, count($searches), '');
        foreach ($searches as $key => $search) {
            $replace = $replacements[$key];
            $search_len = mb_strlen($search, $encoding);

            $sb = [];
            while (($offset = mb_strpos($subject, $search, 0, $encoding)) !== false) {
                $sb[] = mb_substr($subject, 0, $offset, $encoding);
                $subject = mb_substr($subject, $offset + $search_len, null, $encoding);
                ++$count;
            }
            $sb[] = $subject;
            $subject = implode($replace, $sb);
        }

        return $subject;

    }

    private function mbiReplace($search, $replace, $subject, $encoding = 'UTF-8', &$count = 0) {

        $searches = is_array($search) ? array_values($search) : [$search];
        $replacements = is_array($replace) ? array_values($replace) : [$replace];
        $replacements = array_pad($replacements, count($searches), '');
        foreach ($searches as $key => $search) {
            $replace = $replacements[$key];
            $search_len = mb_strlen($search, $encoding);

            $sb = [];
            while (($offset = mb_stripos($subject, $search, 0, $encoding)) !== false) {
                $sb[] = mb_substr($subject, 0, $offset, $encoding);
                $subject = mb_substr($subject, $offset + $search_len, null, $encoding);
                ++$count;
            }
            $sb[] = $subject;
            $subject = implode($replace, $sb);
        }

        return $subject;

    }

    private function removeFormatting($str) {

        $str = preg_replace('/(<font[^>]*>)|(<\/font>)/', '', $str);
        // $str = str_replace('<sup>', '', $str);
        // $str = str_replace('</sup>', '', $str);

        return $str;

    }

}
