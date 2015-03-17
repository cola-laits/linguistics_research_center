<?php 

class LexReflex extends Eloquent {
	protected $table = 'lex_reflex';
	
	public function etymas()
	{
		return $this->belongsToMany('LexEtyma', 'LexEtymaReflex', 'reflex_id', 'etyma_id');
	}
		
	public function language()
	{
		return $this->belongsTo('LexLanguage');
	}
	
	public function parts_of_speech()
	{
		return $this->belongsToMany('LexPartOfSpeech', 'LexReflexPartOfSpeech', 'reflex_id', 'part_of_speech_id');
	}
	
	public function sources()
	{
		return $this->belongsToMany('LexSource', 'LexReflexSource', 'reflex_id', 'source_id');
	}
}