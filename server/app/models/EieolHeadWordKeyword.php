<?php 

class EieolHeadWordKeyword extends Eloquent {
	protected $table = 'eieol_head_word_keyword';
	
	public function series()
	{
		return $this->belongsTo('EieolHeadWord');
	}
}