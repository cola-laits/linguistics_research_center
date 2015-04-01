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
}


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
}

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
		$lessons = EieolLesson::with('glossed_texts.glosses.elements.head_word.language')
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
		$alphabet = $data['language']->custom_sort;
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
		$data['etyma'] = LexEtyma::with('reflexes.language.language_sub_family.language_family','reflexes.sources','reflexes.parts_of_speech','semantic_fields.semantic_category')->find($etyma_id);
	
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
				$sub_poses = explode('.',$pos->code);
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
		$data = array();
		$data['language'] = LexLanguage::with('reflexes.etymas')->find($language_id);
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