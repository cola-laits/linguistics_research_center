<?php 

class EieolGloss extends Eloquent {
	protected $table = 'eieol_gloss';
	
	public function series()
	{
		return $this->belongsTo('EieolHeadWord');
		return $this->belongsToMany('EieolGlossedText', 'eieol_glossed_text');
	}
}