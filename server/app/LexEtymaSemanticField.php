<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LexEtymaSemanticField extends Model {
	protected $table = 'lex_etyma_semantic_field';
	
	public function etyma()
	{
		return $this->hasOne('\App\LexEtyma','id','etyma_id');
	}
	
	public function semantic_field()
	{
		return $this->hasOne('\App\LexSemanticField','id','semantic_field_id');
	}
}
