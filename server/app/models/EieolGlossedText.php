<?php 

class EieolGlossedText extends Eloquent {
	protected $table = 'eieol_glossed_text';
	
	public function lesson()
	{
		return $this->belongsTo('EieolLesson');
	}
	public function glosses()
	{
		return $this->belongsToMany('EieolGloss', 'eieol_glossed_text_gloss', 'glossed_text_id', 'gloss_id')
					->orderBy('eieol_glossed_text_gloss.order')
					->withPivot('order', 'id');
	}
	
	public function clickable_gloss_text()
	{
		//this makes a new version of the glossed text with span tags for each gloss.  
		//Then you can make them clickable so they toggle the gloss.
		$read_str = $this->glossed_text;
		$read_str = str_replace("<br/>", "<br/> ", $read_str);
		$read_str = str_replace("<br />", "<br /> ", $read_str);
		$read_str = str_replace("<br>", "<br> ", $read_str);
		$read_str = str_replace("</p>", "</p> ", $read_str);
		
		$punctuation = array(",",".","!","?",":","(",")");		
		$new_str = '';
		
		foreach($this->glosses as $gloss){

			$start = mb_stripos($read_str, $gloss->surface_form, 0, 'UTF-8');
			$len = mb_strlen($gloss->surface_form, 'UTF-8');
			//print $gloss->surface_form . ' ' . $start . ' ' . $len . '<br/>';
			
			//if you get here, the gloss didn't exactly match the glossed text.  Let's try to guess.
			if ($start === false) {
				$in_tag = false;
				
				//print '____alert<br/>';
				
				//the gloss might contain space.  If so we'll skip that many spaces.
				$num_spaces = substr_count(strip_tags(trim($gloss->surface_form)), ' ');
				
				for ($i=0; $i < mb_strlen($read_str,'UTF-8'); $i++) {
					
					$char = mb_substr($read_str, $i, 1, 'UTF-8');
					//if we are in an html tag, skip until we get out
					if ($in_tag) {
						if ($char == '>') {
							$in_tag = false;
						}
						continue;
					}
					if ($char == '<') {
						$in_tag = true;
						continue;
					}
					
					//skip punctuation
					if (in_array($char, $punctuation)){
						continue;
					}
					
					//set the start as the first non blank character
					if ($start === false && $char != ' ') {
						$start = $i;
					}
					//the end is the first space after the start, unless we have some to skip
					if ($start !== false && $char == ' ') {
						if ($num_spaces > 0) {
							$num_spaces -= 1;
							continue;
						}
						$len = $i-$start;
						break;
					}
				}
				
			}
			
// 			print $read_str . '<br/>' .
// 				  mb_substr($read_str, 0, $start, 'UTF-8') . ' ' . 
// 				  $gloss->surface_form . 
// 				  ' start=' . $start . 
// 				  ' length=' . $len . ' ' . 
// 				  mb_substr($read_str, $start, $len, 'UTF-8') . '<br/>';
		
			//add everything in front of the gloss to the new string
			$new_str .= mb_substr($read_str, 0, $start, 'UTF-8');
		
			//add the gloss with a tag to the new str
			$new_str .= '<a href="#" onclick="return false;" class="click_gloss" id="pivot_' . $gloss->pivot->id . '">' .
					mb_substr($read_str, $start, $len, 'UTF-8') .
					'</a>';
		
			//trim of the front of the old string
			$read_str = mb_substr($read_str, $start + $len, null, 'UTF-8');
			
//    			print $new_str . '<br/>' . $read_str . '<hr/>';
		}
			
		//add rest of old string to end of new string
		$new_str .=  $read_str;
		
		return $new_str;
	} 

}