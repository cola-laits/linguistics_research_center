<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * App\EieolLesson
 *
 * @property int $id
 * @property int $series_id
 * @property string|null $title
 * @property int $order
 * @property int $language_id
 * @property string|null $intro_text
 * @property string|null $lesson_translation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EieolGlossedText[] $glossed_texts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EieolGrammar[] $grammars
 * @property-read \App\EieolLanguage $language
 * @property-read \App\EieolSeries $series
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLesson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLesson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLesson query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLesson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLesson whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLesson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLesson whereIntroText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLesson whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLesson whereLessonTranslation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLesson whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLesson whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLesson whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLesson whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolLesson whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class EieolLesson extends Model {
	protected $table = 'eieol_lesson';

	protected $attributes = array(
			'lesson_translation' => ' '
	);

	public function series()
	{
		return $this->belongsTo('\App\EieolSeries');
	}

	public function grammars()
	{
		return $this->hasMany('\App\EieolGrammar', 'lesson_id', 'id')->orderBy('order');
	}

	public function glossed_texts()
	{
		return $this->hasMany('\App\EieolGlossedText', 'lesson_id', 'id')->orderBy('order');
	}

	public function language()
	{
		return $this->hasOne('\App\EieolLanguage','id','language_id');
	}

	public function getLessonText()
	{
		$lesson_text = '';
		foreach ($this->glossed_texts as $glossed_text) {
			$lesson_text .= $glossed_text->glossed_text . ' ';
		}
		return $this->removeFormatting($lesson_text);
	}

	public function prevLesson()
	{
		return EieolLesson::where('order', '<', $this->order)->where('series_id', '=', $this->series_id)->orderBy('order', 'desc')->first();
	}

	public function nextLesson()
	{
		return EieolLesson::where('order', '>', $this->order)->where('series_id', '=', $this->series_id)->orderBy('order')->first();
	}

  private function removeFormatting($str)
  {

    $str = preg_replace('/(<font[^>]*>)|(<\/font>)/', '', $str);
   // $str = str_replace('<sup>', '', $str);
   // $str = str_replace('</sup>', '', $str);

    return $str;

  }

}
