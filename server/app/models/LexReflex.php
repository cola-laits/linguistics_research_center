<?php 

class LexReflex extends Eloquent {
	protected $table = 'lex_reflex';
	
	public function etymas()
	{
		return $this->belongsToMany('LexEtyma', 'lex_etyma_reflex', 'reflex_id', 'etyma_id')->orderBy('entry');
	}
		
	public function language()
	{
		return $this->belongsTo('LexLanguage');
	}
	
	public function parts_of_speech()
	{
		return $this->belongsToMany('LexPartOfSpeech', 'lex_reflex_part_of_speech', 'reflex_id', 'part_of_speech_id');
	}
	
	public function sources()
	{
		return $this->belongsToMany('LexSource', 'lex_reflex_source', 'reflex_id', 'source_id');
	}
	
	public function getDisplayPartsOfSpeech()
	{
		$string = "";
		$i=0;
		foreach($this->parts_of_speech as $pos){
			$string .= $pos->code;
			$i++;
			if ($i != count($this->parts_of_speech)) {
				$string .= '/';
			}
		}
		return $string;
	}
		
	public function getDisplaySources()
	{
		$string = "";
		$i=0;
		foreach($this->sources as $source){
			$string .= $source->code;
			$i++;
			if ($i != count($this->sources)) {
				$string .= '/';
			}
		}	
		return $string;
	}
}