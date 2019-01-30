<?php 

class LexReflexSource extends Eloquent {
	protected $table = 'lex_reflex_source';
	
	public function reflex()
	{
		return $this->hasOne('LexReflex','id','reflex_id');
	}
	
	public function source()
	{
		return $this->hasOne('LexSource','id','source_id');
	}
}