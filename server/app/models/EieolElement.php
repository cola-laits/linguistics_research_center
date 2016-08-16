<?php 

class EieolElement extends Eloquent {
	protected $table = 'eieol_element';
	
	public function head_word()
	{
		return $this->belongsTo('EieolHeadWord');
	}
	
	public function gloss()
	{
		return $this->belongsTo('EieolGloss');
	}
}