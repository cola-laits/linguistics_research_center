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
    
    $glosses = [];
    foreach($this->glosses as $g) {
      $gloss['surface_form'] = $g->surface_form;
      $gloss['id'] = $g->pivot->id;
      $glosses[] = $gloss;
    }
    
    //order glosses from longest to short for greedy matching!!
    usort( $glosses, 
    function ($a, $b) {
    return strlen($a['surface_form']) < strlen($b['surface_form']);
    } );

		foreach($glosses as $gloss) {
      $read_str = $this->attachClicks($read_str, $gloss['surface_form'], $gloss['id']);
      $read_str = $this->attachClicks($read_str, ucfirst($gloss['surface_form']), $gloss['id']);
    }
		
		return $read_str;
		
	} 
	
	private function attachClicks($str,$g,$id) 
	{
	    $punctuation = array(",",".","!","?",":","(",")","։","՝","յ");	  
	    
	    $a = '<a href="#" onclick="return false;" class="click_gloss" id="pivot_' . $id . '">';
	    
	    foreach ($punctuation as $p) {
        $str = $this->mbReplace(' '.$g.$p, ' '.$a.$g.$p.'</a> ', $str);
        $str = $this->mbReplace($p.$g.' ', $p.$a.$g.'</a> ', $str);
      }
      
      $str = $this->mbReplace(' '.$g.' ', ' '.$a.$g.'</a> ', $str);
      
      if ($this->startsWith($str, $g)) {
        $str = $a.mb_substr($str, 0, mb_strlen($g)).'</a>'.mb_substr($str, mb_strlen($g));
      }
      
      if ($this->endsWith($str, $g)) {
        $str = mb_substr($str, 0, mb_strlen($str) - mb_strlen($g)).$a.$g.'</a>';
      }
      
      return $str;
	}
	
  private function startsWith($haystack, $needle)
  {
       preg_match('/'.preg_quote($needle).'/ui', $haystack, $matches, PREG_OFFSET_CAPTURE);
       
       foreach ($matches as $m) {
          if ($m[1] == 0) return true;
       }
  
       return false;
  }

  private function endsWith($haystack, $needle)
  {
      $length = mb_strlen($needle);

      return $length === 0 || 
      (mb_substr($haystack, - $length) === $needle);
  }
  
  private function mbReplace($search, $replace, $subject, $encoding = 'UTF-8', &$count=0) 
  {
  
  //  $retval = preg_replace('/'.preg_quote($search).'/ui', $replace, $subject);
  //  return $retval;

    if(!is_array($subject)) {
        $searches = is_array($search) ? array_values($search) : [$search];
        $replacements = is_array($replace) ? array_values($replace) : [$replace];
        $replacements = array_pad($replacements, count($searches), '');
        foreach($searches as $key => $search) {
            $replace = $replacements[$key];
            $search_len = mb_strlen($search, $encoding);

            $sb = [];
            while(($offset = mb_strpos($subject, $search, 0, $encoding)) !== false) {
                $sb[] = mb_substr($subject, 0, $offset, $encoding);
                $subject = mb_substr($subject, $offset + $search_len, null, $encoding);
                ++$count;
            }
            $sb[] = $subject;
            $subject = implode($replace, $sb);
        }
    } else {
        foreach($subject as $key => $value) {
            $subject[$key] = self::mbReplace($search, $replace, $value, $encoding, $count);
        }
    }
    return $subject;
    
  }

}