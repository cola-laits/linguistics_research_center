<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LexSemanticCategory extends Model {
	protected $table = 'lex_semantic_category';
	
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
	
	public function semantic_fields()
	{
		return $this->hasMany('\App\LexSemanticField', 'semantic_category_id', 'id')->orderBy('number');
	}
	
}
