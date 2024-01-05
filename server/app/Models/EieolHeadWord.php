<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\EieolHeadWord
 *
 * @property int $id
 * @property string|null $word
 * @property string|null $definition
 * @property int $language_id
 * @property int|null $etyma_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string $keywords
 * @property-read Collection|\App\Models\EieolElement[] $elements
 * @property-read int|null $elements_count
 * @property-read \App\Models\LexEtyma|null $etyma
 * @property-read \App\Models\EieolLanguage $language
 * @method static Builder|EieolHeadWord newModelQuery()
 * @method static Builder|EieolHeadWord newQuery()
 * @method static Builder|EieolHeadWord query()
 * @method static Builder|EieolHeadWord whereCreatedAt($value)
 * @method static Builder|EieolHeadWord whereCreatedBy($value)
 * @method static Builder|EieolHeadWord whereDefinition($value)
 * @method static Builder|EieolHeadWord whereEtymaId($value)
 * @method static Builder|EieolHeadWord whereId($value)
 * @method static Builder|EieolHeadWord whereKeywords($value)
 * @method static Builder|EieolHeadWord whereLanguageId($value)
 * @method static Builder|EieolHeadWord whereUpdatedAt($value)
 * @method static Builder|EieolHeadWord whereUpdatedBy($value)
 * @method static Builder|EieolHeadWord whereWord($value)
 * @mixin Eloquent
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

    protected function getWordWithoutSurroundingAngleBracketsAttribute() {
        return preg_replace('/^<(.*)>$/', '$1', $this->word);
    }
}
