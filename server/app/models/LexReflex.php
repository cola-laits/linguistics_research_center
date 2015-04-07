<?php 

class LexReflex extends Eloquent {
	protected $table = 'lex_reflex';
	
	public function etymas()
	{
		return $this->belongsToMany('LexEtyma', 'lex_etyma_reflex', 'reflex_id', 'etyma_id');
	}
		
	public function entries()
	{
		return $this->hasMany('LexReflexEntry', 'reflex_id', 'id')->orderBy('entry');
	}	
		
	public function language()
	{
		return $this->belongsTo('LexLanguage');
	}
	
	public function parts_of_speech()
	{
		return $this->hasMany('LexReflexPartOfSpeech', 'reflex_id', 'id')->orderBy('order');
	}
	
	public function sources()
	{
		return $this->belongsToMany('LexSource', 'lex_reflex_source', 'reflex_id', 'source_id')->orderBy('order');
	}
	
	public function getDisplayPartsOfSpeech()
	{
		$string = "";
		$i=0;
		foreach($this->parts_of_speech as $pos){
			$string .= $pos->text;
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