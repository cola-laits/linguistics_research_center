<?php 

class EieolGlossedText extends Eloquent {
	protected $table = 'eieol_glossed_text';
	
	public function series()
	{
		return $this->belongsTo('EieolLesson');
		return $this->belongsToMany('EieolGloss', 'eieol_gloss');
	}
}