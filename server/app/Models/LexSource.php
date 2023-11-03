<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\LexReflex;

/**
 * App\Models\LexSource
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $display
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read Collection|\App\Models\LexReflex[] $reflex
 * @property-read int|null $reflex_count
 * @method static Builder|LexSource newModelQuery()
 * @method static Builder|LexSource newQuery()
 * @method static Builder|LexSource query()
 * @method static Builder|LexSource whereCode($value)
 * @method static Builder|LexSource whereCreatedAt($value)
 * @method static Builder|LexSource whereCreatedBy($value)
 * @method static Builder|LexSource whereDisplay($value)
 * @method static Builder|LexSource whereId($value)
 * @method static Builder|LexSource whereUpdatedAt($value)
 * @method static Builder|LexSource whereUpdatedBy($value)
 * @mixin Eloquent
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

    public function lexicon()
    {
        return $this->belongsTo(LexLexicon::class, 'lexicon_id');
    }
}
