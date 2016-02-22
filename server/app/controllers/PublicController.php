<?php

function get_series_info($series_id) {
	//used by many pages to get the series plus all the lessons and languages.
	$data = array();
	$data['series'] = EieolSeries::find($series_id);
	$data['lessons'] = EieolLesson::with('grammars', 'language')->where('series_id', '=', $series_id)->get()->sortBy('order');
	
	$data['languages'] = array();
	$data['bibliography_id'] = '';
	foreach($data['lessons'] as $lesson) {
		if (!in_array($lesson->language, $data['languages'])) {
			$data['languages'][] = $lesson->language;
		}
		if (strpos($lesson->title,'Bibliography') != false) {
			$data['bibliography_id'] = $lesson->id;
		}
	}

	return $data;
} //get_series_info

function blowItUp($custom_sort) {
	$alphabet = array();
	
	//create an arrary where each letter has a value equal to its comma separated position in the string
	$alphabet_groups = explode(',',$custom_sort);
	foreach ($alphabet_groups as $key => $group) {
		$values = explode('=',$group);
		foreach ($values as $value) {
			$alphabet[$value] = str_pad(($key+1), 3, '0', STR_PAD_LEFT); //pad with zeros
		}
	}

	//Now sort the array by length. Each entry can be more than one character long, and we want longest first.
	//That way when we replace them in the sorter, we get the longest ones first, so Ž is not equal to Z and ll is not l.
	$keys = array_map('mb_strlen', array_keys($alphabet));
	array_multisort($keys, SORT_DESC, $alphabet);
	//print_r($alphabet);
	return $alphabet;
}

function alphabet_sorter($a, $b) {
	//key_compare_func for uksort.
	//because we expect unicode, we use multibyte string functions
	
	//because this function is passed by uksort, we pass the alphabet and sort type in a global
	global $alphabet;
	global $sort_type;
	//print '<xmp>' . $a . ' <> ' . $b . '</xmp>';
	
	if ($sort_type == 'master_gloss') {
		//only include what's in front of ' -- '
		$a = mb_substr($a, 0, mb_strpos($a, ' -- ', 0, 'UTF-8'),'UTF-8');
		$b = mb_substr($b, 0, mb_strpos($b, ' -- ', 0, 'UTF-8'),'UTF-8');
	} elseif ($sort_type == 'dictionary') {
		//only include what's in front of '> --'
		$a = mb_split('> --',$a)[0];
		$b = mb_split('> --',$b)[0];
		//remove first character, because it's a '<'
		$a = mb_substr($a,1,Null,'UTF-8');
		$b = mb_substr($b,1,Null,'UTF-8');
	} else {
		print 'unknown sort type of ' . $sort_type;
		exit();
	}
	
	//remove any tags like sup or sub
	$a = strip_tags($a);
	$b = strip_tags($b);
	
	//make a copy of input for messages
	$for_message_a = $a;
	$for_message_b = $b;
	
	///replace any whitespace
	$a = str_replace(' ', '000', $a);
	$b = str_replace(' ', '000', $b);
	
	//remove any commas
	$a = str_replace(',', '000', $a);
	$b = str_replace(',', '000', $b);
	
	//print '<xmp>' . $a . ' <> ' . $b . '</xmp>';
	
	//loop through the alphabet and replace each character with the three digit number of it's position.
	//Remember that we sorted the alphabet by length, so Ž is not equal to Z and ll is not l.
	foreach($alphabet as $letter => $value) {
		$a = str_replace($letter, $value, $a);
		$b = str_replace($letter, $value, $b);
	}
	//print '<xmp>' . $a . ' <> ' . $b . '</xmp>';

	//warn if anything is left.  It needs to be added to the sort order list.
	$astrlen = mb_strlen($a,'UTF-8');
	for( $i = 0; $i < $astrlen; $i++ ) {
		$char = mb_substr($a, $i, 1,'UTF-8');
		if (!is_numeric($char)) {
			//$a = str_replace($char, '', $a);
			print 'unknown sort character of ' . $char . ' at byte ' . ($i/3+1) . ' in <xmp> "' . $for_message_a .'"</xmp>';
			exit();
		}
	}
	$bstrlen = mb_strlen($b,'UTF-8');
	for( $i = 0; $i < $bstrlen; $i++ ) {
		$char = mb_substr($b, $i, 1,'UTF-8');
		if (!is_numeric($char)) {
			//$b = str_replace($char, '', $b);
			print 'unknown sort character of ' . $char . ' at byte ' . ($i/3+1) . ' in <xmp> "' . $for_message_b . '"</xmp>';
			exit();
		}
	}
	
	//pad the shorter one because we're now comparing numbers and the shorter one would always be smaller, which is not what we want.
	if ($astrlen > $bstrlen) {
		$b = str_pad($b, $astrlen, '0', STR_PAD_RIGHT);
	} else {
		$a = str_pad($a, $bstrlen, '0', STR_PAD_RIGHT);
	}
	
	//print '<xmp>' . $a . ' <> ' . $b . '</xmp>';
	//print ($a > $b ? 1 : -1) . "<br/>";
	
	//return 1 if a is bigger, else, -1
	return $a > $b ? 1 : -1;
}

function zalphabet_sorter($a, $b) {
	//key_compare_func for uksort.
	//because we expect unicode, we use multibyte string functions
	//log::error($a . ' ' . $b);
	print ($a . ' <> ' . $b  ."<br/>");

	//because this function is passed by uksort, we pass the alphabet in a global
	global $alphabet;
	
	//get length of each word and see which is shorter
	$aLen = mb_strlen($a,'UTF-8');
	$bLen = mb_strlen($b,'UTF-8');
	$shorterLen = min($aLen, $bLen);

	//loop through shorter length
	for ($i=0; $i < $shorterLen; $i++) {
		print $i . ': ';
		//get i-th character from each word
		$aChar = mb_substr($a, $i, 1, 'UTF-8');
		$bChar = mb_substr($b, $i, 1, 'UTF-8');		
		print ($aChar . '<>' . $bChar ."<br/>");
		//get position in alphabet for each character
		$alpha_ctr = 0;
		$aVal = 0;
		$bVal = 0;
 		foreach ($alphabet as $char) {
 			//log::error($char);
 			$alpha_ctr +=1;
	 		if (mb_strpos($char, $aChar, 0,'UTF-8') !== False) {
	 			$aVal = $alpha_ctr;
	 			//log::error('a=' . $aVal);
	 			//print ($aChar . '=' . $aVal ."<br/>");
	 		}
 			if (mb_strpos($char, $bChar, 0,'UTF-8') !== False) {
	 			$bVal = $alpha_ctr;
	 			//log::error('b=' . $bVal);
	 			//print ($bChar . '=' . $bVal ."<br/>");
 			}
 		}
 		print ('      ' . $aVal . '<>' . $bVal . ' === ');
 		print ($aVal > $bVal ? 1 : -1);
 		print "<br/>";
		//log::error($aChar . ' ' . $aVal . ' ' . $bChar . ' ' . $bVal);

		//return 1 if a is bigger, else, -1
		if ($aVal!=$bVal) {
			return $aVal > $bVal ? 1 : -1;
		}
	}
	//if you get here, the shorter is the same as the longer.
	//so if the shorter is b, return 1
	return $shorterLen==$bLen ? 1 : -1;
} //alphabet_sorter

class PublicController extends BaseController {	
	
	public function index()
	{
		return View::make('index');
	}
	
	//----------------------------------------EIEOL Functions--------------------------------------------
	
	public function eieol()
	{
		$data = array();
		$data['serieses'] = EieolSeries::where('published', '=', True)->get()->sortBy('order');
		return View::make('eieol')->with($data);
	}
	
	public function eieol_lesson($series_id)
	{
		$data = get_series_info($series_id);
		$data['printable'] = False;
		
		if ($data['series']['use_old_gloss_ui']) {
			$data['clickable'] = False;
		} else {
			$data['clickable'] = True;
		}
	
		if (Input::has('id')) {
			$data['lesson'] = EieolLesson::with('grammars','language')
			->with('glossed_texts.glosses.language','glossed_texts.glosses.elements.head_word.language')
			->where('id', '=', Input::get('id'))
			->firstOrFail();
		} else {
			//if they didn't send an id, get the first lesson
			$data['lesson'] = EieolLesson::with('grammars')
			->with('glossed_texts.glosses.language','glossed_texts.glosses.elements.head_word.language')
			->where('series_id', '=', $series_id)
			->orderBy('order')
			->first();
		}
	
		return View::make('eieol_lesson')->with($data);
	}
	
	
	public function eieol_printable($series_id)
	{
		$data = get_series_info($series_id);
		$data['printable'] = True;
		$data['clickable'] = False;
		
		$html = View::make('printable_header_layout');
		
		$lessons = EieolLesson::with('grammars')
			->with('glossed_texts.glosses.language','glossed_texts.glosses.elements.head_word.language')
			->where('series_id', '=', $series_id)
			->orderBy('order')
			->get();
		
		$first = True;
		foreach ($lessons as $lesson) {
			if ($first) {
				$first = False;
			} else {
				$html .= '<div class="printable_footer"></div>';
			}
			
			$data['lesson'] = $lesson;
			$html .= View::make('eieol_lesson')->with($data);
		}
		
		$html .= View::make('printable_footer_layout');
		
		return $html;
	}
	
	
	public function eieol_toc($series_id)
	{
		$data = get_series_info($series_id);
		return View::make('eieol_toc')->with($data);
	}
	
	public function eieol_master_gloss($series_id, $language_id)
	{	
		$data = get_series_info($series_id);
		$data['language'] = EieolLanguage::find($language_id);
		$data['glosses'] = array();
		
		$lessons = EieolLesson::with('glossed_texts.glosses.elements.head_word.language')
		->where('series_id', '=', $series_id)
		->where('language_id', '=', $language_id)
		->select(array('id','title','order'))
		->get()
		->sortBy('order');		
		
		//loop through all the lessons, glossed texts and glosses to group like glosses
		foreach ($lessons as $lesson) {
			foreach ($lesson->glossed_texts as $glossed_text) {
				foreach ($glossed_text->glosses as $gloss) {
					$key = $gloss->surface_form . ' -- ';
					$i = 0;
					foreach($gloss->elements as $element){
						$i++;
						if ($i != 1) {
							$key .= ' + ';
						}
						$key .= ' ' .
								$element->part_of_speech . '; ' .
								$element->analysis . ' ';
					}
					if (!key_exists($key, $data['glosses'])) {
						$data['glosses'][$key] = $gloss->toArray();
						$data['glosses'][$key]['displayGlossForMasterGloss'] = $gloss->getDisplayGlossForMasterGloss();
						$data['glosses'][$key]['glossed_text_gloss_ids'] = array();
						$data['glosses'][$key]['glossed_text_gloss_ids'][$gloss->pivot->id] = $lesson;
					} else {
						$data['glosses'][$key]['glossed_text_gloss_ids'][$gloss->pivot->id] = $lesson;
					}
				}
			}
		}
		
		global $alphabet;
		$alphabet = blowItUp($data['language']->custom_sort);
		global $sort_type;
		$sort_type = 'master_gloss';
		uksort($data['glosses'], 'alphabet_sorter');
		return View::make('eieol_master_gloss')->with($data);
	}
	
	public function eieol_base_form_dictionary($series_id, $language_id)
	{
		$data = get_series_info($series_id);
		$data['language'] = EieolLanguage::find($language_id);
		$lessons = EieolLesson::with('glossed_texts.glosses.elements.head_word.language','glossed_texts.glosses.elements.head_word.etyma')
		->where('series_id', '=', $series_id)
		->where('language_id', '=', $language_id)
		->select(array('id','title','order'))
		->get()
		->sortBy('order');
		
		$data['head_words'] = array();
		//loop through all the lessons, glossed texts and glosses to group like head words
		foreach ($lessons as $lesson) {
			foreach ($lesson->glossed_texts as $glossed_text) {
				foreach ($glossed_text->glosses as $gloss) {
					foreach ($gloss->elements as $element) {
						$key = $element->head_word->word . ' -- ' . $element->head_word->definition;
						if (!key_exists($key, $data['head_words'])) {
							$data['head_words'][$key] = $element->head_word->toArray();
							$data['head_words'][$key]['display'] = $element->head_word->getDisplayHeadWord();
							$data['head_words'][$key]['glossed_text_gloss_ids'] = array();
							$data['head_words'][$key]['glossed_text_gloss_ids'][$gloss->pivot->id] = $lesson;
						} else {
							$data['head_words'][$key]['glossed_text_gloss_ids'][$gloss->pivot->id] = $lesson;
						}
					}
				}
			}
		}
		
		global $alphabet;
		$alphabet = blowItUp($data['language']->custom_sort);
		global $sort_type;
		$sort_type = 'dictionary';
		uksort($data['head_words'],'alphabet_sorter');
		return View::make('eieol_base_form_dictionary')->with($data);
	}
	
	
	public function eieol_english_meaning_index($series_id, $language_id)
	{
		$data = get_series_info($series_id);
		$data['language'] = EieolLanguage::find($language_id);
		$lessons = EieolLesson::with('glossed_texts.glosses.elements.head_word.language','glossed_texts.glosses.elements.head_word.keywords')
		->where('series_id', '=', $series_id)
		->where('language_id', '=', $language_id)
		->select(array('id','title','order'))
		->get()
		->sortBy('order');
		$data['keywords'] = array();
		
		//loop through all the lessons, glossed texts and glosses to group like keywords
		foreach ($lessons as $lesson) {
			foreach ($lesson->glossed_texts as $glossed_text) {
				foreach ($glossed_text->glosses as $gloss) {
					foreach ($gloss->elements as $element) {
						foreach ($element->head_word->keywords as $keyword) {
							$key = $keyword->keyword . ' -- ' . $element->head_word->word . ' -- ' . $element->head_word->definition;
							if (!key_exists($key, $data['keywords'])) {
								$data['keywords'][$key] = $keyword->toArray();
								$data['keywords'][$key]['head_word'] =  $element->head_word->getDisplayHeadWord();
								$data['keywords'][$key]['glossed_text_gloss_ids'] = array();
								$data['keywords'][$key]['glossed_text_gloss_ids'][$gloss->pivot->id] = $lesson;
							} else {
								$data['keywords'][$key]['glossed_text_gloss_ids'][$gloss->pivot->id] = $lesson;
							}
						}
					}
				}
			}
		}
		ksort($data['keywords']);
		return View::make('eieol_english_meaning_index')->with($data);
	}
	
	
	public function eieol_text_list()
	{
		$data = array();
		$data['text_list'] = array();
		
		$serieses = EieolSeries::where('published', '=', True)->get()->sortBy('order');		
		foreach ($serieses as $series) {
			$text = array();
			$text['id'] = $series['id'];
			
			
			$languages = get_series_info($series->id)['languages'];
			if (count($languages) > 1){
				foreach ($languages as $language) {
					$text['title'] = $series['title'] . ' (' . $language['language'] . ')';
					$text['language_id'] = $language['id'];
					$data['text_list'][] = $text;
				}
			} else {
				$text['title'] = $series['title'];
				$text['language_id'] = 0;
				$data['text_list'][] = $text;
			}
		}
		return View::make('eieol_text_list')->with($data);
	}
	
	
	public function eieol_text_toc($series_id)
	{
		$data['series'] = EieolSeries::find($series_id);
		if (Input::has('language_id')) {
			$language = EieolLanguage::find(Input::get('language_id'));
			$data['language_id'] = Input::get('language_id');
			$data['series']['title'] .= ' (' . $language['language'] . ')';
			$data['lessons'] = EieolLesson::with('grammars', 'language')->where('series_id', '=', $series_id)->where('language_id', '=', Input::get('language_id'))->get()->sortBy('order');
		} else {
			$data['language_id'] = 0;
			$data['lessons'] = EieolLesson::with('grammars', 'language')->where('series_id', '=', $series_id)->get()->sortBy('order');
		}
		return View::make('eieol_text_toc')->with($data);
	}
	
	
	public function eieol_text($series_id)
	{
		$data = get_series_info($series_id);	
		if (Input::get('language_id') != 0) {
			$language = EieolLanguage::find(Input::get('language_id'));
			$data['series']['title'] .= ' (' . $language['language'] . ')';
		}
		$data['lesson'] = EieolLesson::with('language')
			->with('glossed_texts.glosses.language','glossed_texts.glosses.elements.head_word.language')
			->where('id', '=', Input::get('id'))
			->firstOrFail();
		
		return View::make('eieol_text')->with($data);
	}

	
	//--------------------------------------------Lexicon Functions-----------------------------------------------
	
	
	public function lex()
	{
		$data = array();
		return View::make('lex');
	}
	
	public function lex_pokorny()
	{		
		$data = array();
		$data['etymas'] = LexEtyma::with('cross_references','reflex_count')->get()->sortBy('order');
		return View::make('lex_pokorny')->with($data);
	}
	
	public function lex_reflex($etyma_id)
	{
		$data = array();
		$data['etyma'] = LexEtyma::with('reflexes.entries',
									    'reflexes.language.language_sub_family.language_family',
									    'reflexes.sources',
									    'reflexes.parts_of_speech',
									    'semantic_fields.semantic_category')->find($etyma_id);
		return View::make('lex_reflex')->with($data);
	}
	
	public function lex_language()
	{
		$data = array();
		$data['language_families'] = LexLanguageFamily::with('language_sub_families.languages.reflex_count')->get()->sortBy('order');
		return View::make('lex_language')->with($data);
	}
	
	public function lex_lang_reflexes($language_id)
	{
		//This is the most complicate code in the whole LRC system
			
		$data = array();
		$data['language'] = LexLanguage::find($language_id);
			
		//get all the reflexes.  The Eloquent ORM is too slow, so we have to write our own SQL
		$temp_reflexes = DB::select( DB::raw("SELECT lex_reflex.id, lex_reflex.class_attribute, lex_reflex.lang_attribute, 
													 lex_reflex_entry.entry, 
													 lex_etyma.entry as etyma_entry, lex_etyma.id as etyma_id, lex_etyma.gloss 
				FROM lex_reflex, lex_reflex_entry, lex_etyma_reflex, lex_etyma 
				WHERE language_id = '$language_id'
				AND lex_reflex_entry.reflex_id = lex_reflex.id 
				AND lex_etyma_reflex.reflex_id = lex_reflex.id 
				AND lex_etyma.id = lex_etyma_reflex.etyma_id") );
		
		$data['display_reflexes'] = array();
		
		//building the list of reflexes is complicated.
		$alpha_weights = $data['language']->getWeights();
		
		foreach($temp_reflexes as $reflex) {
								
			//now build array of reflexes, combining where needed.
			foreach(LexReflexEntry::keys($reflex->entry) as $key) {
				$new_key = LexReflexEntry::hashKey($key, $alpha_weights);
				
				//if 2 reflexes are the same, group them
				if (array_key_exists($new_key,$data['display_reflexes'])) {
					$temp_etyma = array();
					$temp_etyma['entry'] = $reflex->etyma_entry;
					$temp_etyma['gloss'] = $reflex->gloss;
					$temp_etyma['id'] = $reflex->etyma_id;
					$data['display_reflexes'][$new_key]['etymas'][] = $temp_etyma;
					ksort($data['display_reflexes'][$new_key]['etymas']); //sort the etymas
				} else {
					$new_reflex = array();
					$new_reflex['id'] = $reflex->id;
					$new_reflex['reflex'] = $key;
					$new_reflex['class_attribute'] = $reflex->class_attribute;
					$new_reflex['lang_attribute'] = $reflex->lang_attribute;
					$new_reflex['etymas'] = array();
					$temp_etyma = array();
					$temp_etyma['entry'] = $reflex->etyma_entry;
					$temp_etyma['gloss'] = $reflex->gloss;
					$temp_etyma['id'] = $reflex->etyma_id;
					$new_reflex['etymas'][] = $temp_etyma;
					
					$data['display_reflexes'][$new_key] = $new_reflex;
				}
			} //foreach key
		} //foreach reflex
		
		//we have to use a string sort or it will think these are ints and shortest entries will come first
 		ksort($data['display_reflexes'], $sort_flags = SORT_STRING); 
		
		return View::make('lex_lang_reflexes')->with($data);
	}
	
	public function lex_semantic()
	{
		$data = array();
		$data['cats'] = LexSemanticCategory::get()->sortBy('number');
		$data['alpha_cats'] = LexSemanticCategory::get()->sortBy('text');
		return View::make('lex_semantic')->with($data);
	}
	
	public function lex_semantic_category($cat_id)
	{
		$data = array();
		$data['cat'] = LexSemanticCategory::find($cat_id);
		$data['alpha_cats'] = LexSemanticCategory::get()->sortBy('text');
		$data['fields'] = LexSemanticField::with('etyma_count')->where('semantic_category_id', '=', $cat_id)->get()->sortBy('number');
		return View::make('lex_semantic_category')->with($data);
	}
	
	public function lex_semantic_field($field_id)
	{
		$data = array();
		$data['field'] = LexSemanticField::with('etymas.reflex_count','semantic_category')->find($field_id);
		$data['alpha_cats'] = LexSemanticCategory::get()->sortBy('text');
		return View::make('lex_semantic_field')->with($data);
	}
	
	
	
	//--------------------------------------------REST functions for mobile app-----------------------------------------------
	
	public function rest_eieol_serieses()
	{
		$return_serieses = array();
		$serieses = EieolSeries::where('published', '=', True)->get()->sortBy('order');
		foreach($serieses as $series) {
			$temp_dict = array();
			$temp_dict['title'] = $series->title;
			$temp_dict['id'] = $series->id;
			$return_serieses[] = $temp_dict;
		} 
		return Response::json($return_serieses);
	}
	
	public function rest_eieol_series($series_id)
	{
		$return_toc = array();
		$data = get_series_info($series_id);
		foreach($data['lessons'] as $lesson) {
			$temp_dict = array();
			$temp_dict['title'] = $lesson->title;
			$temp_dict['id'] = $lesson->id;
			$return_toc[] = $temp_dict;
		}
		return Response::json($return_toc);
	}
	
	public function rest_eieol_lesson($lesson_id)
	{
		$return_lesson = array();
		$lesson = EieolLesson::with('grammars','language')
			->with('glossed_texts.glosses.language','glossed_texts.glosses.elements.head_word.language')
			->where('id', '=', $lesson_id)
			->firstOrFail();
		$return_lesson['title'] = $lesson->title;
		$return_lesson['intro_text'] = $lesson->intro_text;
		$return_lesson['lesson_text'] = $lesson->getLessonText();
		$return_lesson['lesson_translation'] = $lesson->lesson_translation;
		
		$return_lesson['grammars'] = array();
		foreach($lesson->grammars as $grammar) {
			$return_grammar = array();
			$return_grammar['title'] = $grammar->title;
			$return_grammar['section_number'] = $grammar->section_number;
			$return_grammar['grammar_text'] = $grammar->grammar_text;
			$return_lesson['grammars'][] = $return_grammar;
		}
		
		$return_lesson['glossed_texts'] = array();
		foreach($lesson->glossed_texts as $glossed_text) {
			$return_glossed_text = array();
			$return_glossed_text['id'] = $glossed_text->id;
			$return_glossed_text['glossed_text'] = $glossed_text->glossed_text;
			$return_glossed_text['clickable_gloss_text'] = $glossed_text->clickable_gloss_text();
			$return_glossed_text['glosses'] = array();
			foreach ($glossed_text->glosses as $gloss) {
				$return_gloss = array();
				$return_gloss['pivot_id'] = $gloss->pivot->id;
				$return_gloss['display_gloss'] = $gloss->getDisplayGloss();
				$return_glossed_text['glosses'][] = $return_gloss;
			}
			$return_lesson['glossed_texts'][] = $return_glossed_text;
		}
		return Response::json($return_lesson);
	}
	
}