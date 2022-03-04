<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EieolElement;
use App\Models\EieolLanguage;
use App\Models\LexEtyma;

/**
 * App\Models\EieolHeadWord
 *
 * @property int $id
 * @property string|null $word
 * @property string|null $definition
 * @property int $language_id
 * @property int|null $etyma_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string $keywords
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EieolElement[] $elements
 * @property-read int|null $elements_count
 * @property-read \App\Models\LexEtyma|null $etyma
 * @property-read \App\Models\EieolLanguage $language
 * @method static \Illuminate\Database\Eloquent\Builder|EieolHeadWord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolHeadWord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolHeadWord query()
 * @method static \Illuminate\Database\Eloquent\Builder|EieolHeadWord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolHeadWord whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolHeadWord whereDefinition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolHeadWord whereEtymaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolHeadWord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolHeadWord whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolHeadWord whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolHeadWord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolHeadWord whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EieolHeadWord whereWord($value)
 * @mixin \Eloquent
 */
class EieolHeadWord extends Model {
	protected $table = 'eieol_head_word';

	public function elements()
	{
		return $this->hasMany(EieolElement::class, 'head_word_id', 'id');
	}

	public function language()
	{
		return $this->belongsTo(EieolLanguage::class);
	}

	public function etyma()
	{
		return $this->belongsTo(LexEtyma::class);
	}
}
