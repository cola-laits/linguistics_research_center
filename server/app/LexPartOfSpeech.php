<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\LexPartOfSpeech
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $display
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LexReflex[] $reflex
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexPartOfSpeech newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexPartOfSpeech newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexPartOfSpeech query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexPartOfSpeech whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexPartOfSpeech whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexPartOfSpeech whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexPartOfSpeech whereDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexPartOfSpeech whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexPartOfSpeech whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LexPartOfSpeech whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class LexPartOfSpeech extends Model {
	protected $table = 'lex_part_of_speech';

	protected $fillable = ['code','display'];
	
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
		return $this->hasMany('\App\LexReflex', 'part_of_speech_id', 'id');
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
