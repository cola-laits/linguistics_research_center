<?php 

class LexEtymaReflex extends Eloquent {
	protected $table = 'lex_etyma_reflex';
	
	public function etyma()
	{
		return $this->hasOne('LexEtyma','id','etyma_id');
	}
	
	public function reflex()
	{
		return $this->hasOne('LexReflex','id','reflex_id');
	}
}