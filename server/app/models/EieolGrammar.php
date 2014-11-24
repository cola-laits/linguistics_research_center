<?php 

class EieolGrammar extends Eloquent {
	protected $table = 'eieol_grammar';
	
	public function series()
	{
		return $this->belongsTo('EieolLesson');
	}
}