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
	
	public function language()
	{
		return $this->belongsTo('EieolLanguage');
	}
	
	public function etyma()
	{
		return $this->belongsTo('LexEtyma');
	}
	
	public function getDisplayHeadWord()
	{
		//return headword and definition in the format <nobr>&lt;<span lang='cu' class='Cyrillic'>Ñ¥Ñ�-, Ñ¥Ñ�Ð¼ÑŒ, Ñ¥Ñ�Ð¸</span>&gt;</nobr> be
		//trim <>
		$word = $this->word;
		$word = substr($word,1);
		$word = substr($word,0,-1);
		
		return "<nobr>&lt;<span lang='" . $this->language->lang_attribute . "' class='" . $this->language->class_attribute . "'>" . $word .  "</span>&gt;</nobr> "  . $this->definition;
	}
}
