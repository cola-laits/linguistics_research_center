<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EieolGrammar extends Model {
	protected $table = 'eieol_grammar';
	
	public function lesson()
	{
		return $this->belongsTo('\App\EieolLesson');
	}
}
