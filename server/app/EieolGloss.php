<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EieolGloss
 *
 * @property int $id
 * @property string|null $surface_form
 * @property string|null $contextual_gloss
 * @property string|null $comments
 * @property string|null $underlying_form
 * @property int $language_id
 * @property string|null $author_comments
 * @property int|null $author_done
 * @property string|null $admin_comments
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EieolElement[] $elements
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EieolGlossedText[] $glossed_texts
 * @property-read \App\EieolLanguage $language
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereAdminComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereAuthorComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereAuthorDone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereContextualGloss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereSurfaceForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereUnderlyingForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class EieolGloss extends Model {
	protected $table = 'eieol_gloss';
	
	public function glossed_texts()
	{
		return $this->belongsToMany('\App\EieolGlossedText', 'eieol_glossed_text_gloss', 'gloss_id', 'glossed_text_id')->withPivot('order', 'id');
	}
	
	public function elements()
	{
		return $this->hasMany('\App\EieolElement', 'gloss_id', 'id')->orderBy('order');
	}
	
	public function language()
	{
		return $this->belongsTo('\App\EieolLanguage');
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
			$string .= ' # ' . $this->cleanHTML($this->comments);
			//$string .= ' # ' . $this->comments;
		}
		
		if ($this->underlying_form) {
			$string .= ' <br/><span lang="' . $this->language->lang_attribute . '" class="' . $this->language->class_attribute . '" style="margin-left:10px;">(' . $this->underlying_form . ')</span>';
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
	
	private function cleanHTML($html) 
	{
	  
    libxml_use_internal_errors(true);

    $dom = new \DOMDocument();
    $dom->loadHTML('<?xml encoding="utf-8" ?><root>' . $html . '</root>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $xpath = new \DOMXPath($dom);

    foreach( $xpath->query('//*[not(node())]') as $node ) {
        $node->parentNode->removeChild($node);
    }
    
    return $dom->saveHTML();
	  
	}
	
}
