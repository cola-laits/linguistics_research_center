<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\LexEtymaReflex
 *
 * @property int $id
 * @property int $etyma_id
 * @property int $reflex_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\LexEtyma $etyma
 * @property-read \App\LexReflex $reflex
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaReflex newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaReflex newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaReflex query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaReflex whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaReflex whereEtymaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaReflex whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaReflex whereReflexId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexEtymaReflex whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LexEtymaReflex extends Model {
	protected $table = 'lex_etyma_reflex';
	
	public function etyma()
	{
		return $this->hasOne('\App\LexEtyma','id','etyma_id');
	}
	
	public function reflex()
	{
		return $this->hasOne('\App\LexReflex','id','reflex_id');
	}
}
