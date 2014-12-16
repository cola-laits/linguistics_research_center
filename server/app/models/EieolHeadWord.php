<?php 

class EieolHeadWord extends Eloquent {
	protected $table = 'eieol_head_word';
	
	public function keywords()
	{
		return $this->hasMany('EieolHeadWordKeyword', 'head_word_id', 'id')->orderBy('keyword');
	}
	
	public function glosses()
	{
		return $this->hasMany('EieolGloss', 'head_word_id', 'id')->orderBy('surface_form');
	}
	
	public function getDisplayHeadWord()
	{
		return htmlentities($this->word) . ' ' .
				$this->definition;
	}
}