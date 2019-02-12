<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\LexReflexSource
 *
 * @property int $id
 * @property int $reflex_id
 * @property int $source_id
 * @property int $order
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\LexReflex $reflex
 * @property-read \App\LexSource $source
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexSource query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexSource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexSource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexSource whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexSource whereReflexId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexSource whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexSource whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LexReflexSource extends Model {
	protected $table = 'lex_reflex_source';
	
	public function reflex()
	{
		return $this->hasOne('\App\LexReflex','id','reflex_id');
	}
	
	public function source()
	{
		return $this->hasOne('\App\LexSource','id','source_id');
	}
}
