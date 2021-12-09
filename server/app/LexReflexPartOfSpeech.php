<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\LexReflexPartOfSpeech
 *
 * @property int $id
 * @property int $reflex_id
 * @property string|null $text
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \App\LexPartOfSpeech $part_of_speech
 * @property-read \App\LexReflex $reflex
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexPartOfSpeech newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexPartOfSpeech newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexPartOfSpeech query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexPartOfSpeech whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexPartOfSpeech whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexPartOfSpeech whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexPartOfSpeech whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexPartOfSpeech whereReflexId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexPartOfSpeech whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexPartOfSpeech whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexReflexPartOfSpeech whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexReflexPartOfSpeech extends Model {
	protected $table = 'lex_reflex_part_of_speech';

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
		return $this->belongsTo('\App\LexReflex');
	}

	public function part_of_speech()
	{
		return $this->hasOne('\App\LexPartOfSpeech','id','part_of_speech_id');
	}
}
