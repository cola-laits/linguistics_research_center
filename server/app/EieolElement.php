<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EieolElement extends Model {
	protected $table = 'eieol_element';
	
	public function head_word()
	{
		return $this->belongsTo('\App\EieolHeadWord');
	}
	
	public function gloss()
	{
		return $this->belongsTo('\App\EieolGloss');
	}
}
