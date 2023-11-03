<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\EieolLesson;
use Illuminate\Support\Carbon;

/**
 * App\Models\EieolGrammar
 *
 * @property int $id
 * @property int $lesson_id
 * @property string|null $title
 * @property int $order
 * @property string|null $grammar_text
 * @property string|null $section_number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \App\Models\EieolLesson $lesson
 * @method static Builder|EieolGrammar newModelQuery()
 * @method static Builder|EieolGrammar newQuery()
 * @method static Builder|EieolGrammar query()
 * @method static Builder|EieolGrammar whereCreatedAt($value)
 * @method static Builder|EieolGrammar whereCreatedBy($value)
 * @method static Builder|EieolGrammar whereGrammarText($value)
 * @method static Builder|EieolGrammar whereId($value)
 * @method static Builder|EieolGrammar whereLessonId($value)
 * @method static Builder|EieolGrammar whereOrder($value)
 * @method static Builder|EieolGrammar whereSectionNumber($value)
 * @method static Builder|EieolGrammar whereTitle($value)
 * @method static Builder|EieolGrammar whereUpdatedAt($value)
 * @method static Builder|EieolGrammar whereUpdatedBy($value)
 * @mixin Eloquent
 */
class EieolGrammar extends Model {
	protected $table = 'eieol_grammar';

	public function lesson()
	{
		return $this->belongsTo(EieolLesson::class);
	}
}
