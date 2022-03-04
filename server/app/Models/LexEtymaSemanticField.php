<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LexEtyma;
use App\Models\LexSemanticField;

/**
 * App\Models\LexEtymaSemanticField
 *
 * @property int $id
 * @property int $etyma_id
 * @property int $semantic_field_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\LexEtyma|null $etyma
 * @property-read \App\Models\LexSemanticField|null $semantic_field
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaSemanticField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaSemanticField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaSemanticField query()
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaSemanticField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaSemanticField whereEtymaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaSemanticField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaSemanticField whereSemanticFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaSemanticField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LexEtymaSemanticField extends Model {
	protected $table = 'lex_etyma_semantic_field';

	public function etyma()
	{
		return $this->hasOne(LexEtyma::class, 'id', 'etyma_id');
	}

	public function semantic_field()
	{
		return $this->hasOne(LexSemanticField::class, 'id', 'semantic_field_id');
	}
}
