<?php 

class EieolGloss extends Eloquent {
	protected $table = 'eieol_gloss';
	
	public function head_word()
	{
		return $this->belongsTo('EieolHeadWord');
	}
	
	
	public function glossed_texts()
	{
		return $this->belongsToMany('EieolGlossedText', 'eieol_glossed_text_gloss', 'gloss_id', 'glossed_text_id')->withPivot('order');
	}
}