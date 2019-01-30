<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EieolPartOfSpeech extends Model {
	protected $table = 'eieol_part_of_speech';
	
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
}
