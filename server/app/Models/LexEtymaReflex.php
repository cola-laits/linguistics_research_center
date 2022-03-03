<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LexEtyma;
use App\Models\LexReflex;

/**
 * App\Models\LexEtymaReflex
 *
 * @property int $id
 * @property int $etyma_id
 * @property int $reflex_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\LexEtyma|null $etyma
 * @property-read \App\Models\LexReflex|null $reflex
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaReflex newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaReflex newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaReflex query()
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaReflex whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaReflex whereEtymaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaReflex whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaReflex whereReflexId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexEtymaReflex whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LexEtymaReflex extends Model {
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
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
