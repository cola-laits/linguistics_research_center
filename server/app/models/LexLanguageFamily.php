<?php 

class LexLanguageFamily extends Eloquent {
	protected $table = 'lex_language_family';
	
	public function language_sub_families()
	{
		return $this->hasMany('LexLanguageSubFamily', 'family_id', 'id')->orderBy('order');
	}
}