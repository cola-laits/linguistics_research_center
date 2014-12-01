<?php 

class EieolHeadWordKeyword extends Eloquent {
	protected $table = 'eieol_head_word_keyword';
	
	public function head_word()
	{
		return $this->belongsTo('EieolHeadWord');
	}
}