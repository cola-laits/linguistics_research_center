<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EieolGrammar
 *
 * @property int $id
 * @property int $lesson_id
 * @property string|null $title
 * @property int $order
 * @property string|null $grammar_text
 * @property string|null $section_number
 * @property string|null $author_comments
 * @property int|null $author_done
 * @property string|null $admin_comments
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \App\EieolLesson $lesson
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGrammar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGrammar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGrammar query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGrammar whereAdminComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGrammar whereAuthorComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGrammar whereAuthorDone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGrammar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGrammar whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGrammar whereGrammarText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGrammar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGrammar whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGrammar whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGrammar whereSectionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGrammar whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGrammar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGrammar whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class EieolGrammar extends Model {
	protected $table = 'eieol_grammar';
	
	public function lesson()
	{
		return $this->belongsTo('\App\EieolLesson');
	}
}
