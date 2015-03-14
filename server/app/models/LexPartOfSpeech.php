<?php 

class LexPartOfSpeech extends Eloquent {
	protected $table = 'lex_part_of_speech';
	
	public function reflex()
	{
		return $this->hasMany('LexReflex', 'part_of_speech_id', 'id');
	}
}