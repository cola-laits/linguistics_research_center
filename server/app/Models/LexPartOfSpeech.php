<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\LexReflex;

/**
 * App\Models\LexPartOfSpeech
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
 * @method static \Illuminate\Database\Eloquent\Builder|LexPartOfSpeech newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexPartOfSpeech newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexPartOfSpeech query()
 * @method static \Illuminate\Database\Eloquent\Builder|LexPartOfSpeech whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexPartOfSpeech whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexPartOfSpeech whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexPartOfSpeech whereDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexPartOfSpeech whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexPartOfSpeech whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexPartOfSpeech whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexPartOfSpeech extends Model {

    use CrudTrait;

	protected $table = 'lex_part_of_speech';

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
		return $this->hasMany(LexReflex::class, 'part_of_speech_id', 'id');
	}

	public static function posLookup()
	{
		$all_pos = LexPartOfSpeech::all();
		$pos_lookup = array();
		foreach ($all_pos as $pos) {
			$pos_lookup[$pos->code] = $pos->display;
		}
		return $pos_lookup;
	}
}
