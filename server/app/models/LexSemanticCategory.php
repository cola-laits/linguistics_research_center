<?php 

class LexSemanticCategory extends Eloquent {
	protected $table = 'lex_semantic_category';
	
	public function semantic_fields()
	{
		return $this->hasMany('LexSemanticField', 'semantic_category_id', 'id')->orderBy('number');
	}
}