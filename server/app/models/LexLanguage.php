<?php 

class LexLanguage extends Eloquent {
	protected $table = 'lex_language';
	
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
	
	public function language_sub_family()
	{
		return $this->belongsTo('LexLanguageSubFamily','sub_family_id');
	}
	
	public function reflexes()
	{
		return $this->hasMany('LexReflex', 'language_id', 'id');
	}
	
	public function small_reflexes()
	{
		return $this->hasMany('LexReflex', 'language_id', 'id');
	}
	
	public function reflex_count()
	{
		return $this->hasMany('LexReflex', 'language_id', 'id')->select(DB::raw('language_id, count(*) as count'))->groupBy('language_id');
	}
}