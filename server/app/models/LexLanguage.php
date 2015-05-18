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
	
	public function getStrippedNameAttribute()
	{
		return strip_tags($this->name);
	}
	
	public function getWeights()
	{
		//each language has a custom sort array.  We are going to re-index it with weights.  ie a->1, b->2
		$alpha_weights = array();
		$alphabet = explode(',',$this->custom_sort);
		
		$ctr = 0;
		foreach($alphabet as $alpha) {
			$ctr += 1;
			for( $i = 0; $i <= mb_strlen($alpha, 'UTF-8'); $i++ ) {
				$alpha_weights[mb_substr($alpha, $i, 1, 'UTF-8')] = $ctr;
			}
		}
		return $alpha_weights;
	}
	
	public function displayFamily()
	{
		if ($this->override_family != '') {
			return $this->override_family;
		} else {
			return $this->language_sub_family->language_family->name;
		}
	}
}