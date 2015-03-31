<?php 

class LexLanguageSubFamily extends Eloquent {
	protected $table = 'lex_language_sub_family';
	
	public function languages()
	{
		return $this->hasMany('LexLanguage', 'sub_family_id', 'id')->orderBy('order');
	}
	
	public function language_family()
	{
		return $this->belongsTo('LexLanguageFamily','family_id');
	}
	
}