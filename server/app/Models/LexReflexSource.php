<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LexReflex;
use App\Models\LexSource;

/**
 * App\Models\LexReflexSource
 *
 * @property int $id
 * @property int $reflex_id
 * @property int $source_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\LexReflex|null $reflex
 * @property-read \App\Models\LexSource|null $source
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexSource query()
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexSource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexSource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexSource whereReflexId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexSource whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexSource whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LexReflexSource extends Model {
	protected $table = 'lex_reflex_source';

	public function reflex()
	{
		return $this->hasOne(LexReflex::class, 'id', 'reflex_id');
	}

	public function source()
	{
		return $this->hasOne(LexSource::class, 'id', 'source_id');
	}
}
