<?php 

class LexSemanticField extends Eloquent {
	protected $table = 'lex_semantic_field';
	
	public static function boot() {
		parent::boot();
	
		// event to happen on saving
		static::creating(function($table)  {
			$table->created_by = Auth::user()->getUsername();
			$table->updated_by = Auth::user()->getUsername();
		});
	
		// event to happen on updating
		static::updating(function($table)  {
			$table->updated_by = Auth::user()->getUsername();
		});
	}
	
	public function semantic_category()
	{
		return $this->belongsTo('LexSemanticCategory');
	}
	
	public function etymas()
	{
		return $this->belongsToMany('LexEtyma', 'lex_etyma_semantic_field', 'semantic_field_id', 'etyma_id')->orderBy('order');;
	}	
	
	public function etyma_count()
	{
		return $this->belongsToMany('LexEtyma', 'lex_etyma_semantic_field', 'semantic_field_id', 'etyma_id')->selectRaw('count(etyma_id) as count')->groupBy('pivot_semantic_field_id');
	
	}
	
}