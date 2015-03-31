<?php 

class LexEtyma extends Eloquent {
	protected $table = 'lex_etyma';
	
	public function semantic_fields()
	{
		return $this->belongsToMany('LexSemanticField', 'lex_etyma_semantic_field', 'etyma_id', 'semantic_field_id');
	}
	
	public function reflexes()
	{
		return $this->belongsToMany('LexReflex', 'lex_etyma_reflex', 'etyma_id', 'reflex_id');
	}
	
	public function reflex_count()
	{
		return $this->belongsToMany('LexReflex', 'lex_etyma_reflex', 'etyma_id', 'reflex_id')->selectRaw('count(reflex_id) as count')->groupBy('pivot_etyma_id');
		
	}
	
	public function cross_references()
	{
		return $this->belongsToMany('LexEtyma', 'lex_etyma_cross_reference', 'from_etyma_id', 'to_etyma_id');
	}
		
}