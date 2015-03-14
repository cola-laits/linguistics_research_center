<?php 

class LexSource extends Eloquent {
	protected $table = 'lex_source';
	
	public function reflex()
	{
		return $this->hasMany('LexReflex', 'source_id', 'id');
	}
}