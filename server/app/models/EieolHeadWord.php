<?php 

class EieolHeadWord extends Eloquent {
	protected $table = 'eieol_head_word';
	
	public function keywords()
	{
		return $this->hasMany('EieolHeadWordKeyword', 'head_word_id', 'id')->orderBy('keyword');
	}
	
	public function elements()
	{
		return $this->hasMany('EieolElement', 'head_word_id', 'id');
	}
	
	public function getDisplayHeadWord()
	{
		//trim <> and replace with &chars
		$word = $this->word;
		$word = substr($word,1);
		$word = substr($word,0,-1);
		$word = "&lt;" . $word . "&gt;";
		
		return $word . ' ' . $this->definition;
	}
}
