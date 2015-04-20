<?php

function get_series_info($series_id) {
	$data = array();
	$data['series'] = EieolSeries::find($series_id);
	$data['lessons'] = EieolLesson::with('grammars', 'language')->where('series_id', '=', $series_id)->get()->sortBy('order');

	$data['languages'] = array();
	foreach($data['lessons'] as $lesson) {
		if (!in_array($lesson->language, $data['languages'])) {
			$data['languages'][] = $lesson->language;
		}
	}

	return $data;
} //get_series_info


function alphabet_sorter($a, $b) {
	//key_compare_func for uksort.
	//because we expect unicode, we use multibyte string functions
	//log::error($a . ' ' . $b);

	//because this function is passed by uksort, we pass the alphabet in a global
	global $alphabet;
	
	//get length of each word and see which is shorter
	$aLen = mb_strlen($a,'UTF-8');
	$bLen = mb_strlen($b,'UTF-8');
	$shorterLen = min($aLen, $bLen);

	//loop through shorter length
	for ($i=0; $i < $shorterLen; $i++) {

		//get i-th character from each word
		$aChar = mb_substr($a, $i, 1, 'UTF-8');
		$bChar = mb_substr($b, $i, 1, 'UTF-8');		

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
	 		}
 			if (mb_strpos($char, $bChar, 0,'UTF-8') !== False) {
	 			$bVal = $alpha_ctr;
	 			//log::error('b=' . $bVal);
 			}
 		}
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

function split_entries($entry) {
	$open = mb_strpos($entry,'(', 0,'UTF-8');
	$close = mb_strpos($entry,')', 0,'UTF-8');
	$first = mb_substr($entry, 0, $open, 'UTF-8');
	
	$len = $close - $open;
	$middle = mb_substr($entry, $open + 1, $len - 1, 'UTF-8');
	
	$len = mb_strlen($entry, 'UTF-8') - $close;
	$last = mb_substr($entry, $close + 1, $len, 'UTF-8');
	
	$short = $first . $last;
	$long = $first . $middle . $last;
	
	$keys = array();
	
	if (mb_strpos($short,'(', 0,'UTF-8') === False) {
		$keys[] = $short;
	} else {
		//print_r(split_entries($short));
		$keys = array_merge($keys,split_entries($short));
	}
	
	if (mb_strpos($long,'(', 0,'UTF-8') === False) {
		$keys[] = $long;
	} else {
		$keys = array_merge($keys,split_entries($long));
	}
	
	return $keys;
} //split_entries function

class PublicController extends BaseController {	
	
	public function index()
	{
		return View::make('index');
	}
	
	//--------------------------------------------------------------------------------------------------
	
	public function eieol()
	{
		$data = array();
		$data['serieses'] = EieolSeries::where('published', '=', True)->get()->sortBy('order');
		return View::make('eieol')->with($data);
	}
	
	public function eieol_lesson($series_id)
	{
		$data = get_series_info($series_id);
	
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
		
		//build lesson_text
		$data['lesson_text'] = '';
		foreach ($data['lesson']->glossed_texts as $glossed_text) {
			$data['lesson_text'] .= $glossed_text->glossed_text . ' ';
		}
	
		return View::make('eieol_lesson')->with($data);
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
		$lessons = EieolLesson::with('glossed_texts.glosses.elements.head_word.language')
		->where('series_id', '=', $series_id)
		->where('language_id', '=', $language_id)
		->select(array('id','title','order'))
		->get()
		->sortBy('order');
		$data['glosses'] = array();
		foreach ($lessons as $lesson) {
			foreach ($lesson->glossed_texts as $glossed_text) {
				foreach ($glossed_text->glosses as $gloss) {
					$key = $gloss->surface_form;
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
		$alphabet = explode(',',$data['language']->custom_sort);
		//Log::error($alphabet);
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
		$alphabet = explode(',',$data['language']->custom_sort);
		//Log::error($alphabet);
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
	
	
	//--------------------------------------------------------------------------------------------------
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
	
		//set which lang to display
		foreach ($data['etyma']->reflexes as $reflex) {
			if ($reflex->language->override_family != '') {
				$reflex->display_family = $reflex->language->override_family;
			} else {
				$reflex->display_family = $reflex->language->language_sub_family->language_family->name;
			}
			
		}
		
		//build list of sources used by these reflexes
		$sources = array();
		foreach ($data['etyma']->reflexes as $reflex) {
			foreach($reflex->sources as $source) {
				if (!array_key_exists($source->code,$sources)) {
					$sources[$source->code] = $source->display;
				}
			}
		}
		ksort($sources);
		$data['sources'] = $sources;
	
		//build list of parts of speech used by these reflexes.  This is a little more complicate.
		//A single pos might be made up of several.  So we buld a lookup list first.
		//then we break up the used pos and lookup each part.
		$all_pos = LexPartOfSpeech::all();
		$pos_lookup = array();
		foreach ($all_pos as $pos) {
			$pos_lookup[$pos->code] = $pos->display;
		}
	
		$poses = array();
		foreach ($data['etyma']->reflexes as $reflex) {
			foreach($reflex->parts_of_speech as $pos) {
				$sub_poses = explode('.',$pos->text);
				foreach($sub_poses as $sub_pos) {
					if (!array_key_exists($sub_pos,$poses)) {
						$poses[$sub_pos] = $pos_lookup[$sub_pos];
					}
				}
			}
		}
		ksort($poses);
		$data['poses'] = $poses;
	
		//get next and previous etyma
		$data['prev_etyma'] = LexEtyma::where('order', '<', $data['etyma']->order)->orderBy('order', 'desc')->first();
		$data['next_etyma'] = LexEtyma::where('order', '>', $data['etyma']->order)->orderBy('order')->first();
	
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
		
		//these characters will not be used when sorting the keys of the array
		$the_unwanted = array("-", "*", "'");
		
		$data = array();
		$data['language'] = LexLanguage::find($language_id);
			
		//each language has a custom sort array.  We are going to reindex it with weights.  ie a->1, b->2
		$alphabet = explode(',',$data['language']->custom_sort);
		$alpha_weights = array();
		$ctr = 0;
		foreach($alphabet as $alpha) {
			$ctr += 1;
			for( $i = 0; $i <= mb_strlen($alpha, 'UTF-8'); $i++ ) {
				$alpha_weights[mb_substr($alpha, $i, 1, 'UTF-8')] = $ctr;
			}
		}

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
		foreach($temp_reflexes as $reflex) {
			$keys=array();
			//special processing based on whether or not the entry has a ( in it
			if (mb_strpos($reflex->entry,'(', 0,'UTF-8') === False) {
				//regular entry
				$keys[] = $reflex->entry;
			} else {
				//if a reflex contains characters in (), split into 2, ex (g)nosco = gnosco and nosco				
				$keys = split_entries($reflex->entry);
			}
						
			//now build array of reflexes, combining where needed.
			//also, convert the key reflex to a series of numbers based on the weighted alphabet array for easy sorting.
			foreach($keys as $key) {
				
				//convert key to an array of numbers for easy searching
				//break string into an array
				$key_array = preg_split('//u',$key, -1, PREG_SPLIT_NO_EMPTY);

				$new_key = '';
				foreach($key_array as $key_char) {
					//remove any unwanted characters to the end.  
					if (in_array($key_char,$the_unwanted)) {
						continue;
					} elseif (array_key_exists($key_char,$alpha_weights)) {
						$new_key .= str_pad($alpha_weights[$key_char], 4,'0', STR_PAD_LEFT);
					} else {
						$new_key .= '0000';
					}
				}
				//Tack the original entry on to the end.  This way the keys remain unique but the ending isn't really used for sorting
				$new_key .= $key;
				//print $key . ' ' . $new_key . '<br/>';
				
				//if 2 reflexes are the same, group them
				if (array_key_exists($new_key,$data['display_reflexes'])) {
					$temp_etyma = array();
					$temp_etyma['entry'] = $reflex->etyma_entry;
					$temp_etyma['gloss'] = $reflex->gloss;
					$temp_etyma['id'] = $reflex->etyma_id;
					$data['display_reflexes'][$new_key]['etymas'][] = $temp_etyma;
					ksort($data['display_reflexes'][$new_key]['etymas']);
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
	
}