<?php 

class EieolGloss extends Eloquent {
	protected $table = 'eieol_gloss';
	
	public function head_word()
	{
		return $this->belongsTo('EieolHeadWord');
	}
	
	
	public function glossed_texts()
	{
		return $this->belongsToMany('EieolGlossedText', 'eieol_glossed_text_gloss', 'gloss_id', 'glossed_text_id')->withPivot('order', 'id');
	}
	
	/**
	 * Format the gloss in the way they are accustomed
	 *
	 * @return string
	 */
	public function getDisplayGloss()
	{
		return $this->surface_form . ' -- ' .
				$this->part_of_speech . '; ' .
				$this->analysis . ' ' .
				htmlentities($this->head_word->word) . ' ' .
				$this->head_word->definition .
				'<strong> -- ' . $this->contextual_gloss . '</strong>';
	}
}