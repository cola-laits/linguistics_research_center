<?php 

class EieolGlossedTextGloss extends Eloquent {
	protected $table = 'eieol_glossed_text_gloss';
	
	public function glossed_text()
	{
		return $this->hasOne('EieolGlossedText','id','glossed_text_id');
	}
	
	public function gloss()
	{
		return $this->hasOne('EieolGloss','id','gloss_id');
	}
}