<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LexEtymaReflex extends Model {
	protected $table = 'lex_etyma_reflex';
	
	public function etyma()
	{
		return $this->hasOne('\App\LexEtyma','id','etyma_id');
	}
	
	public function reflex()
	{
		return $this->hasOne('\App\LexReflex','id','reflex_id');
	}
}
