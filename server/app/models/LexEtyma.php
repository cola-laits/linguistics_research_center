<?php 

class LexEtyma extends Eloquent {
	protected $table = 'lex_etyma';
	
	public function semantic_fields()
	{
		return $this->belongsToMany('LexSemanticField', 'LexEtymaSemanticField', 'etyma_id', 'semantic_field_id');
	}
	
	public function reflexes()
	{
		return $this->belongsToMany('LexReflex', 'LexEtymaReflex', 'etyma_id', 'reflex_id');
	}
	
	public function cross_references()
	{
		return $this->belongsToMany('LexEtyma', 'LexCrossReference', 'from_etman_id', 'to_etyma_id');
	}
	
}