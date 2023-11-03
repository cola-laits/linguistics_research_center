<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\LexEtyma;
use App\Models\LexReflex;
use Illuminate\Support\Carbon;

/**
 * App\Models\LexEtymaReflex
 *
 * @property int $id
 * @property int $etyma_id
 * @property int $reflex_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\LexEtyma|null $etyma
 * @property-read \App\Models\LexReflex|null $reflex
 * @method static Builder|LexEtymaReflex newModelQuery()
 * @method static Builder|LexEtymaReflex newQuery()
 * @method static Builder|LexEtymaReflex query()
 * @method static Builder|LexEtymaReflex whereCreatedAt($value)
 * @method static Builder|LexEtymaReflex whereEtymaId($value)
 * @method static Builder|LexEtymaReflex whereId($value)
 * @method static Builder|LexEtymaReflex whereReflexId($value)
 * @method static Builder|LexEtymaReflex whereUpdatedAt($value)
 * @mixin Eloquent
 */
class LexEtymaReflex extends Model {
    use CrudTrait;
	protected $table = 'lex_etyma_reflex';

    protected $guarded = [
        'id','created_at','created_by','updated_at','updated_by'
    ];

	public function etyma()
	{
		return $this->hasOne(LexEtyma::class, 'id', 'etyma_id');
	}

	public function reflex()
	{
		return $this->hasOne(LexReflex::class, 'id', 'reflex_id');
	}
}
