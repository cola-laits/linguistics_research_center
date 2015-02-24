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
	
	/**
	 * Format the gloss in the way they are accustomed
	 *
	 * @return string
	 */
	public function getDisplayGloss()
	{
		$string = $this->surface_form . ' -- ';
		$i=0;
		foreach($this->elements as $element){
			$i++;
			if ($i != 1) {
				$string .= ' + ';
			}
			$string .= $element->part_of_speech . '; ' .
						$element->analysis . ' ' .
						htmlentities($element->head_word->word) . ' ' .
						$element->head_word->definition;
		}
		$string .= '<strong> -- ' . $this->contextual_gloss . '</strong>';
		
		if ($this->comments) {
			$string .= ' # ' . $this->comments;
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
					htmlentities($element->head_word->word) . ' ' .
					$element->head_word->definition;
		}
		return $string;
	}
}
