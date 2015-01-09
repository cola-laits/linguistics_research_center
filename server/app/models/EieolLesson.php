<?php 

class EieolLesson extends Eloquent {
	protected $table = 'eieol_lesson';
	
	public function series()
	{
		return $this->belongsTo('EieolSeries');
	}
	
	public function grammars()
	{
		return $this->hasMany('EieolGrammar', 'lesson_id', 'id')->orderBy('order');
	}
	
	public function glossed_texts()
	{
		return $this->hasMany('EieolGlossedText', 'lesson_id', 'id')->orderBy('order');
	}
	
	public function language()
	{
		return $this->hasOne('EieolLanguage','id','language_id');
	}
}