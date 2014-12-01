<?php 

class EieolGrammar extends Eloquent {
	protected $table = 'eieol_grammar';
	
	public function lesson()
	{
		return $this->belongsTo('EieolLesson');
	}
}