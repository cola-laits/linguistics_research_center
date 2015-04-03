<?php 

class LexReflexEntry extends Eloquent {
	protected $table = 'lex_reflex_entry';
			
	public function reflex()
	{
		return $this->belongsTo('LexReflex');
	}
	
}