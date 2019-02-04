<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\LexEtymaSemanticField
 *
 * @property int $id
 * @property int $etyma_id
 * @property int $semantic_field_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\LexEtyma $etyma
 * @property-read \App\LexSemanticField $semantic_field
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaSemanticField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaSemanticField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaSemanticField query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaSemanticField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaSemanticField whereEtymaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaSemanticField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaSemanticField whereSemanticFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaSemanticField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LexEtymaSemanticField extends Model {
	protected $table = 'lex_etyma_semantic_field';
	
	public function etyma()
	{
		return $this->hasOne('\App\LexEtyma','id','etyma_id');
	}
	
	public function semantic_field()
	{
		return $this->hasOne('\App\LexSemanticField','id','semantic_field_id');
	}
}
