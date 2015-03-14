<?php 

class LexEtymaSemanticField extends Eloquent {
	protected $table = 'lex_etyma_semantic_field';
	
	public function etyma()
	{
		return $this->hasOne('LexEtyma','id','etyma_id');
	}
	
	public function semantic_field()
	{
		return $this->hasOne('LexSemanticField','id','semantic_field_id');
	}
}