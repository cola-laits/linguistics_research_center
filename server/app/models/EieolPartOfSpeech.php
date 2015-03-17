<?php 

class EieolPartOfSpeech extends Eloquent {
	protected $table = 'eieol_part_of_speech';
	
	public function reflexes()
	{
		return $this->belongsToMany('LexReflex', 'LexEtymaReflex', 'part_of_speech_id', 'reflex_id');
	}
}