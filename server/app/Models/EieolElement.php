<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EieolHeadWord;
use App\Models\EieolGloss;

/**
 * App\Models\EieolElement
 *
 * @property int $id
 * @property int $gloss_id
 * @property string|null $part_of_speech
 * @property string|null $analysis
 * @property int $head_word_id
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \App\Models\EieolGloss $gloss
 * @property-read \App\Models\EieolHeadWord $head_word
 * @method static \Illuminate\Database\Eloquent\Builder|EieolElement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolElement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolElement query()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolElement whereAnalysis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolElement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolElement whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolElement whereGlossId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolElement whereHeadWordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolElement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolElement whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolElement wherePartOfSpeech($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolElement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolElement whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class EieolElement extends Model {
	protected $table = 'eieol_element';

	public function head_word()
	{
		return $this->belongsTo(EieolHeadWord::class);
	}

	public function gloss()
	{
		return $this->belongsTo(EieolGloss::class);
	}
}
