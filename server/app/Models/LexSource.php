<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\LexReflex;

/**
 * App\Models\LexSource
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $display
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LexReflex[] $reflex
 * @property-read int|null $reflex_count
 * @method static \Illuminate\Database\Eloquent\Builder|LexSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexSource query()
 * @method static \Illuminate\Database\Eloquent\Builder|LexSource whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSource whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSource whereDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSource whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexSource whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexSource extends Model {

    use CrudTrait;

	protected $table = 'lex_source';

	protected $guarded = ['id'];

	public static function boot() {
		parent::boot();

		// event to happen on saving
		static::creating(function($table)  {
			$table->created_by = Auth::user()->username;
			$table->updated_by = Auth::user()->username;
		});

		// event to happen on updating
		static::updating(function($table)  {
			$table->updated_by = Auth::user()->username;
		});

	}

	public function reflex()
	{
		return $this->hasMany(LexReflex::class, 'source_id', 'id');
	}
}
