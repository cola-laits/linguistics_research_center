<?php 

class LexReflex extends Eloquent {
	protected $table = 'lex_reflex';
	
	public function etymas()
	{
		return $this->belongsToMany('LexEtyma', 'LexEtymaReflex', 'reflex_id', 'etyma_id');
	}
	
	public function source()
	{
		return $this->belongsTo('LexSource');
	}
	
	public function part_of_speech()
	{
		return $this->belongsTo('LexPartOfSpeech');
	}
	
	public function belongsTo()
	{
		return $this->belongsTo('LexLanguage');
	}
}