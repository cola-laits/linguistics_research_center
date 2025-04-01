<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\LexEtyma;
use App\Models\LexSemanticField;
use Illuminate\Support\Carbon;

/**
 * App\Models\LexEtymaSemanticField
 *
 * @property int $id
 * @property int $etyma_id
 * @property int $semantic_field_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\LexEtyma|null $etyma
 * @property-read \App\Models\LexSemanticField|null $semantic_field
 * @method static Builder|LexEtymaSemanticField newModelQuery()
 * @method static Builder|LexEtymaSemanticField newQuery()
 * @method static Builder|LexEtymaSemanticField query()
 * @method static Builder|LexEtymaSemanticField whereCreatedAt($value)
 * @method static Builder|LexEtymaSemanticField whereEtymaId($value)
 * @method static Builder|LexEtymaSemanticField whereId($value)
 * @method static Builder|LexEtymaSemanticField whereSemanticFieldId($value)
 * @method static Builder|LexEtymaSemanticField whereUpdatedAt($value)
 * @mixin Eloquent
 */
class LexEtymaSemanticField extends Model {
	protected $table = 'lex_etyma_semantic_field';

    protected $guarded = ['id'];

	public function etyma()
	{
		return $this->hasOne(LexEtyma::class, 'id', 'etyma_id');
	}

	public function semantic_field()
	{
		return $this->hasOne(LexSemanticField::class, 'id', 'semantic_field_id');
	}
}
