<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\EieolPartOfSpeech
 *
 * @property int $id
 * @property string|null $part_of_speech
 * @property int $language_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolPartOfSpeech newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolPartOfSpeech newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolPartOfSpeech query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolPartOfSpeech whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolPartOfSpeech whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolPartOfSpeech whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolPartOfSpeech whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolPartOfSpeech wherePartOfSpeech($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolPartOfSpeech whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolPartOfSpeech whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class EieolPartOfSpeech extends Model {
	protected $table = 'eieol_part_of_speech';
	
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
}
