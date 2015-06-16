<?php

function get_series_info($series_id) {
	//used by many pages to get the series plus all the lessons and languages.
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
		$alphabet = explode(',',$data['language']->custom_sort);
		
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
		return Response::json(EieolSeries::where('published', '=', True)->get()->sortBy('order'));
	}
	
}