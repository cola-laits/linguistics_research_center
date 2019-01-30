<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EieolGlossedTextGloss extends Model {
	protected $table = 'eieol_glossed_text_gloss';
	
	public function glossed_text()
	{
		return $this->hasOne('\App\EieolGlossedText','id','glossed_text_id');
	}
	
	public function gloss()
	{
		return $this->hasOne('\App\EieolGloss','id','gloss_id');
	}
}
