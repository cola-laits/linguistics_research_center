<?php 

class EieolLesson extends Eloquent {
	protected $table = 'eieol_lesson';
	
	protected $attributes = array(
			'lesson_translation' => ' '
	);
	
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
	
	public function getLessonText()
	{
		$lesson_text = '';
		foreach ($this->glossed_texts as $glossed_text) {
			$lesson_text .= $glossed_text->glossed_text . ' ';
		}
		return $lesson_text;
	}
	
	public function prevLesson()
	{
		return EieolLesson::where('order', '<', $this->order)->where('series_id', '=', $this->series_id)->orderBy('order', 'desc')->first();
	}
	
	public function nextLesson()
	{
		return EieolLesson::where('order', '>', $this->order)->where('series_id', '=', $this->series_id)->orderBy('order')->first();
	}
}