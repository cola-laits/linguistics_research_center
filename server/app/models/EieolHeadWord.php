<?php 

class EieolHeadWord extends Eloquent {
	protected $table = 'eieol_head_word';
	
	public function keywords()
	{
		return $this->hasMany('EieolHeadWordKeyword', 'head_word_id', 'id')->orderBy('keyword');
	}
	
	public function getDisplayHeadWord()
	{
		return htmlentities($this->word) . ' ' .
				$this->definition;
	}
}