<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EieolGlossedText;
use App\Models\EieolGrammar;
use App\Models\EieolSeries;

/**
 * App\Models\EieolLesson
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EieolGlossedText[] $glossed_texts
 * @property-read int|null $glossed_texts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EieolGrammar[] $grammars
 * @property-read int|null $grammars_count
 * @property-read \App\Models\EieolLanguage|null $language
 * @property-read \App\Models\EieolSeries $series
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLesson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLesson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLesson query()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLesson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLesson whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLesson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLesson whereIntroText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLesson whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLesson whereLessonTranslation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLesson whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLesson whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLesson whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLesson whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolLesson whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class EieolLesson extends Model {
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
	protected $table = 'eieol_lesson';

	protected $attributes = array(
			'lesson_translation' => ' '
	);

    protected $guarded = ['id'];

    public function series()
	{
		return $this->belongsTo(EieolSeries::class);
	}

	public function grammars()
	{
		return $this->hasMany(EieolGrammar::class, 'lesson_id', 'id')->orderBy('order');
	}

	public function glossed_texts()
	{
		return $this->hasMany(EieolGlossedText::class, 'lesson_id', 'id')->orderBy('order');
	}

	public function language()
	{
		return $this->belongsTo(EieolLanguage::class,'language_id');
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
