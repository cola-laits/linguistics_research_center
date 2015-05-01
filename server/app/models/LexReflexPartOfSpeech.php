<?php 

class LexReflexPartOfSpeech extends Eloquent {
	protected $table = 'lex_reflex_part_of_speech';
	
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
		return $this->belongsTo('LexReflex');
	}
	
	public function part_of_speech()
	{
		return $this->hasOne('LexPartOfSpeech','id','part_of_speech_id');
	}
}