<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EieolLesson;

/**
 * App\Models\EieolGrammar
 *
 * @property int $id
 * @property int $lesson_id
 * @property string|null $title
 * @property int $order
 * @property string|null $grammar_text
 * @property string|null $section_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \App\Models\EieolLesson $lesson
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGrammar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGrammar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGrammar query()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGrammar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGrammar whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGrammar whereGrammarText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGrammar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGrammar whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGrammar whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGrammar whereSectionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGrammar whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGrammar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolGrammar whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class EieolGrammar extends Model {
	protected $table = 'eieol_grammar';

	public function lesson()
	{
		return $this->belongsTo(EieolLesson::class);
	}
}
