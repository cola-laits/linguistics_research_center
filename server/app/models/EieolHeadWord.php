<?php 

class EieolHeadWord extends Eloquent {
	protected $table = 'eieol_head_word';
	
	public function getDisplayHeadWord()
	{
		return htmlentities($this->word) . ' ' .
				$this->definition;
	}
}