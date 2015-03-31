<?php 

class LexLanguage extends Eloquent {
	protected $table = 'lex_language';
	
	public function language_sub_family()
	{
		return $this->belongsTo('LexLanguageSubFamily','sub_family_id');
	}
	
	public function reflex()
	{
		return $this->hasMany('LexReflex', 'language_id', 'id');
	}
}