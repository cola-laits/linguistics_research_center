<?php 

class LexReflexPartOfSpeech extends Eloquent {
	protected $table = 'lex_reflex_part_of_speech';
	
	public function reflex()
	{
		return $this->hasOne('LexReflex','id','reflex_id');
	}
	
	public function part_of_speech()
	{
		return $this->hasOne('LexPartOfSpeech','id','part_of_speech_id');
	}
}