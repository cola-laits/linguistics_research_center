<?php 

class EieolHeadWordKeyword extends Eloquent {
	protected $table = 'eieol_head_word_keyword';
	protected $fillable = array('keyword', 'language_id', 'created_by', 'updated_by');
	
	public function head_word()
	{
		return $this->belongsTo('EieolHeadWord');
	}
}