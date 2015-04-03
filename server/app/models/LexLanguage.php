<?php 

class LexLanguage extends Eloquent {
	protected $table = 'lex_language';
	
	public function language_sub_family()
	{
		return $this->belongsTo('LexLanguageSubFamily','sub_family_id');
	}
	
	public function reflexes()
	{
		return $this->hasMany('LexReflex', 'language_id', 'id');
	}
	
	public function reflex_count()
	{
		return $this->hasMany('LexReflex', 'language_id', 'id')->select(DB::raw('language_id, count(*) as count'))->groupBy('language_id');
	}
}