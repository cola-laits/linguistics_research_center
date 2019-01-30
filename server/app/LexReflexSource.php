<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LexReflexSource extends Model {
	protected $table = 'lex_reflex_source';
	
	public function reflex()
	{
		return $this->hasOne('\App\LexReflex','id','reflex_id');
	}
	
	public function source()
	{
		return $this->hasOne('\App\LexSource','id','source_id');
	}
}
