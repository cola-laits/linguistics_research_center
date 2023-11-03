<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\EieolHeadWord;
use App\Models\EieolGloss;
use Illuminate\Support\Carbon;

/**
 * App\Models\EieolElement
 *
 * @property int $id
 * @property int $gloss_id
 * @property string|null $part_of_speech
 * @property string|null $analysis
 * @property int $head_word_id
 * @property int $order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \App\Models\EieolGloss $gloss
 * @property-read \App\Models\EieolHeadWord $head_word
 * @method static Builder|EieolElement newModelQuery()
 * @method static Builder|EieolElement newQuery()
 * @method static Builder|EieolElement query()
 * @method static Builder|EieolElement whereAnalysis($value)
 * @method static Builder|EieolElement whereCreatedAt($value)
 * @method static Builder|EieolElement whereCreatedBy($value)
 * @method static Builder|EieolElement whereGlossId($value)
 * @method static Builder|EieolElement whereHeadWordId($value)
 * @method static Builder|EieolElement whereId($value)
 * @method static Builder|EieolElement whereOrder($value)
 * @method static Builder|EieolElement wherePartOfSpeech($value)
 * @method static Builder|EieolElement whereUpdatedAt($value)
 * @method static Builder|EieolElement whereUpdatedBy($value)
 * @mixin Eloquent
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
