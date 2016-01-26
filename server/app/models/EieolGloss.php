<?php 

class EieolGloss extends Eloquent {
	protected $table = 'eieol_gloss';
	
	public function glossed_texts()
	{
		return $this->belongsToMany('EieolGlossedText', 'eieol_glossed_text_gloss', 'gloss_id', 'glossed_text_id')->withPivot('order', 'id');
	}
	
	public function elements()
	{
		return $this->hasMany('EieolElement', 'gloss_id', 'id')->orderBy('order');
	}
	
	public function language()
	{
		return $this->belongsTo('EieolLanguage');
	}
	
	/**
	 * Format the gloss in the way they are accustomed
	 *
	 * @return string
	 */
	public function getDisplayGloss()
	{
		//this should return something in the form 
		//<span lang='cu' class='Cyrillic'>Ñ”Ñ�Ñ‚ÑŠ</span> <span style="white-space: nowrap">--</span> verb; 3rd person singular present of <nobr>&lt;<span lang='cu' class='Cyrillic'>Ñ¥Ñ�-, Ñ¥Ñ�Ð¼ÑŒ, Ñ¥Ñ�Ð¸</span>&gt;</nobr> be <nobr>--</nobr> <strong>is</strong>
		//part of which is handled by the getDisplayHeadWord function in the HeadWord model
		
		$string = "<span lang='" . $this->language->lang_attribute . "' class='" . $this->language->class_attribute . "'>" .
				 $this->surface_form . '</span> <span style="white-space: nowrap">--</span> ';
		$i=0;
		foreach($this->elements as $element){
			$i++;
			if ($i != 1) {
				$string .= ' + ';
			}
			
			$string .= $element->part_of_speech . '; ' .
						$element->analysis . ' ' .
						$element->head_word->getDisplayHeadWord();
		}
		$string .= ' <span style="white-space: nowrap">--</span> <strong>' . $this->contextual_gloss . '</strong>';
		
		if ($this->comments) {
			$string .= ' # ' . $this->comments;
		}
		
		if ($this->underlying_form) {
			$string .= ' <br/> ' . $this->underlying_form;
		}
		
		return $string;
	}
	
	
	public function getDisplayGlossForMasterGloss()
	{
		$string = '';
		$i=0;
		foreach($this->elements as $element){
			$i++;
			if ($i != 1) {
				$string .= ' + ';
			}
			
			$string .= $element->part_of_speech . '; ' .
					$element->analysis . ' ' .
					$element->head_word->getDisplayHeadWord();
		}
		return $string;
	}
}
