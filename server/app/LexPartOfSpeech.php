<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LexPartOfSpeech extends Model {
	protected $table = 'lex_part_of_speech';
	
	public static function boot() {
		parent::boot();
	
		// event to happen on saving
		static::creating(function($table)  {
			$table->created_by = Auth::user()->getUsername();
			$table->updated_by = Auth::user()->getUsername();
		});
	
		// event to happen on updating
		static::updating(function($table)  {
			$table->updated_by = Auth::user()->getUsername();
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
