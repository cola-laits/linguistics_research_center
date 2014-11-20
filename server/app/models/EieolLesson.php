<?php 

class EieolLesson extends Eloquent {
	protected $table = 'eieol_lesson';
	
	public function series()
	{
		return $this->belongsTo('EieolSeries');
	}
}