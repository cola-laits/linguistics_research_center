<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\LexReflex;
use App\Models\LexSource;
use Illuminate\Support\Carbon;

/**
 * App\Models\LexReflexSource
 *
 * @property int $id
 * @property int $reflex_id
 * @property int $source_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\LexReflex|null $reflex
 * @property-read \App\Models\LexSource|null $source
 * @method static Builder|LexReflexSource newModelQuery()
 * @method static Builder|LexReflexSource newQuery()
 * @method static Builder|LexReflexSource query()
 * @method static Builder|LexReflexSource whereCreatedAt($value)
 * @method static Builder|LexReflexSource whereId($value)
 * @method static Builder|LexReflexSource whereReflexId($value)
 * @method static Builder|LexReflexSource whereSourceId($value)
 * @method static Builder|LexReflexSource whereUpdatedAt($value)
 * @mixin Eloquent
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
