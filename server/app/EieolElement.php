<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EieolElement
 *
 * @property int $id
 * @property int $gloss_id
 * @property string|null $part_of_speech
 * @property string|null $analysis
 * @property int $head_word_id
 * @property int $order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \App\EieolGloss $gloss
 * @property-read \App\EieolHeadWord $head_word
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolElement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolElement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolElement query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolElement whereAnalysis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolElement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolElement whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolElement whereGlossId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolElement whereHeadWordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolElement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolElement whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolElement wherePartOfSpeech($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolElement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolElement whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class EieolElement extends Model {
	protected $table = 'eieol_element';
	
	public function head_word()
	{
		return $this->belongsTo('\App\EieolHeadWord');
	}
	
	public function gloss()
	{
		return $this->belongsTo('\App\EieolGloss');
	}
}
