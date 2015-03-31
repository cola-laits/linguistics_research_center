<?php 

class LexSemanticField extends Eloquent {
	protected $table = 'lex_semantic_field';
	
	public function semantic_category()
	{
		return $this->belongsTo('LexSemanticCategory');
	}
	
	public function etymas()
	{
		return $this->belongsToMany('LexEtyma', 'lex_etyma_semantic_field', 'semantic_field_id', 'etyma_id');
	}
	
}