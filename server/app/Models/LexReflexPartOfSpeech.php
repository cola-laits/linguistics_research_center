<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\LexReflex;
use App\Models\LexPartOfSpeech;

/**
 * App\Models\LexReflexPartOfSpeech
 *
 * @property int $id
 * @property int $reflex_id
 * @property string|null $text
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \App\Models\LexPartOfSpeech|null $part_of_speech
 * @property-read \App\Models\LexReflex $reflex
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexPartOfSpeech newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexPartOfSpeech newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexPartOfSpeech query()
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexPartOfSpeech whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexPartOfSpeech whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexPartOfSpeech whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexPartOfSpeech whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexPartOfSpeech whereReflexId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexPartOfSpeech whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexPartOfSpeech whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LexReflexPartOfSpeech whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexReflexPartOfSpeech extends Model {

    use CrudTrait;

	protected $table = 'lex_reflex_part_of_speech';

    protected $guarded = [
        'id','created_at','created_by','updated_at','updated_by'
    ];

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
		return $this->belongsTo(LexReflex::class);
	}

	public function part_of_speech()
	{
		return $this->hasOne(LexPartOfSpeech::class, 'id', 'part_of_speech_id');
	}
}
