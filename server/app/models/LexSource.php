<?php 

class LexSource extends Eloquent {
	protected $table = 'lex_source';
	
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
		return $this->hasMany('LexReflex', 'source_id', 'id');
	}
}