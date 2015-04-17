<?php

function mb_trim( $string )
{
	$string = preg_replace( "/(^\s+)|(\s+$)/us", "", $string );

	return $string;
}

function print_series($series_id) {
	print '<code><pre>';
	$series = EieolSeries::with('lessons.grammars')
	->with('lessons.glossed_texts.glosses.elements.head_word.keywords')
	->where('id', '=', $series_id)->get()[0];
	print $series->title . '<br/>';
	foreach($series->lessons as $lesson) {
		print '   ' . $lesson->title . '<br/>';
		foreach ($lesson->grammars as $grammar) {
			print '      ' . $grammar->title . '<br/>';
		} //end grammar
		print '---<br/>';
		foreach ($lesson->glossed_texts as $glossed_text) {
			print '      ' . $glossed_text->glossed_text . '<br/>';
			foreach ($glossed_text->glosses as $gloss) {
				print '         ' . $gloss->surface_form . '<br/>';
				foreach($gloss->elements as $element){
					print '            ' . $element->part_of_speech . '<br/>';
					print '               ' . htmlentities($element->head_word->word) . '<br/>';
					foreach ($element->head_word->keywords as $keyword){
						print '                  ' . $keyword->keyword . '<br/>';
					} //end keywords
				} //end elements
			} //end gloss
		} //end gloss text
		print '-------------------------<br/>';
	} //end lesson
	print '</pre></code>';
} // end print series function

function delete_series_children($series_id) {
	DB::transaction(function($series_id) use($series_id) {
		$series = EieolSeries::with('lessons.grammars')
		->with('lessons.glossed_texts.glosses.elements.head_word.keywords')
		->where('id', '=', $series_id)->get()[0];
		print $series->title . '<br/>';
		
		foreach($series->lessons as $lesson) {
			foreach ($lesson->grammars as $grammar) {
				$grammar->delete();
			} //end grammar
			foreach ($lesson->glossed_texts as $glossed_text) {
				foreach ($glossed_text->glosses as $gloss) {
					foreach($gloss->elements as $element){
						foreach ($element->head_word->keywords as $keyword){
							$keyword->delete();
						} //end keywords
						$temp_head_word_id = $element->head_word->id;
						//delete any element that uses the head word, otherwise foreign key restraints kick in
						EieolElement::where("head_word_id",$temp_head_word_id)->delete();
						if (EieolHeadWord::find($temp_head_word_id)) {
							EieolHeadWord::find($temp_head_word_id)->delete();
						}
					} //end elements
					EieolGlossedTextGloss::where("gloss_id",$gloss->id)->delete();
					$gloss->delete();
				} //end gloss
				$glossed_text->delete();
			} //end gloss text
			$lesson->delete();
		} //end lesson
	}); //end transaction
} // end delete series function

function store_lessons($series) {
	$myfile = fopen($series['path'], "r") or die("Unable to open file!");
	$data = json_decode(fread($myfile,filesize($series['path'])));
	$stored_lessons = array();
	$stored_glosses = array();
	$stored_head_words = array();
	for($i = 0; $i<count($data); $i++) {
		$lesson = $data[$i];
		print $i . ' ' . $lesson->title . '<br/>';
		$new_lesson = new EieolLesson;
		$new_lesson->title =  Normalizer::normalize($lesson->title, Normalizer::FORM_D );
		$new_lesson->order = $lesson->order * 10;
		$new_lesson->series_id = $series['series_id'];
		if (array_key_exists('language_id',$series) ) {
			$temp_language_id = $series['language_id'];
		} else {
			$temp_language_id = $series['language_id_' . (string) $lesson->order];
		}
		$new_lesson->language_id = $temp_language_id;
		$new_lesson->intro_text = Normalizer::normalize($lesson->intro_text, Normalizer::FORM_D );
		$new_lesson->lesson_translation =  Normalizer::normalize($lesson->lesson_translation, Normalizer::FORM_D );
		$new_lesson->created_by = 'loader';
		$new_lesson->updated_by = 'loader';
		
		//Tocharian intro has a link to TOC.
		if ($series['series_id'] == 15 && $i == 0) {
			$new_lesson->intro_text = str_replace('tokol-TC-X.html', '/eieol_toc/15', $new_lesson->intro_text);
		}
		
		//Sanskrit Appendix 1 has link to intro
		if ($series['series_id'] == 10 && $i == 11) {
			$new_lesson->intro_text = str_replace('vedol-0-X.html', '/eieol_lesson/10', $new_lesson->intro_text);
		}
		
		//Sanskrit Appendix 2 has a bunch of links that need to be update
		if ($series['series_id'] == 10 && $i == 12) {
			for($j = 1; $j<=10; $j++) {
				$new_lesson->intro_text = str_replace('vedol-' . (string)$j . '-X.html', '/eieol_lesson/10?id=' . (string)$stored_lessons[$j], $new_lesson->intro_text);
			}
		}
		
		$new_lesson->save();
		$stored_lessons[$i] = $new_lesson->id;
		
		//load glossed_texts
		if (array_key_exists('glossed_texts',$lesson) ) {
			foreach ($lesson->glossed_texts as $glossed_text){
				$new_glossed_text = new EieolGlossedText;
				$new_glossed_text->lesson_id = $new_lesson->id;
				$new_glossed_text->glossed_text = Normalizer::normalize($glossed_text->glossed_text, Normalizer::FORM_D );
				$new_glossed_text->order = $glossed_text->order * 10;
				$new_glossed_text->created_by = 'loader';
				$new_glossed_text->updated_by = 'loader';
				$new_glossed_text->save();
				
				foreach ($glossed_text->glosses as $gloss) {
					$new_glossed_text_gloss = new EieolGlossedTextGloss;
					$surface_form = Normalizer::normalize($gloss->surface_form, Normalizer::FORM_D );
					$part_of_speech = $gloss->elements[0]->part_of_speech;
					$contextual_gloss = Normalizer::normalize($gloss->contextual_gloss, Normalizer::FORM_D );
					
					if (array_key_exists('analysis',$gloss->elements[0])) {
						$analysis = $gloss->elements[0]->analysis;
					} else {
						$analysis = '';
					}
					
					//get first headword
					foreach ($gloss->elements as $element) {
						$head_word = Normalizer::normalize($element->head_word->word, Normalizer::FORM_D );
						break;
					}
					
					$gloss_key = $surface_form . '~~~' .  $part_of_speech . '~~~' . $analysis . '~~~' . $contextual_gloss . '~~~' . $head_word . '~~~' . $temp_language_id;
					//log::error($gloss_key);
					
					if (array_key_exists($gloss_key,$stored_glosses)) {
						$new_glossed_text_gloss->gloss_id = $stored_glosses[$gloss_key];
					} else {					
						$new_gloss = new EieolGloss;
						$new_gloss->surface_form = $surface_form;
						$new_gloss->contextual_gloss = $contextual_gloss;
						if (array_key_exists('comments',$gloss) ) {
							$new_gloss->comments = Normalizer::normalize($gloss->comments, Normalizer::FORM_D );
						}
						$new_gloss->language_id = $temp_language_id;
						$new_gloss->created_by = 'loader';
						$new_gloss->updated_by = 'loader';
						$new_gloss->save();
						$stored_glosses[$gloss_key] = $new_gloss->id;
						$new_glossed_text_gloss->gloss_id = $new_gloss->id;
						
						foreach ($gloss->elements as $element) {
							$new_element = new EieolElement;
						
							$word = Normalizer::normalize($element->head_word->word, Normalizer::FORM_D );
							$definition = Normalizer::normalize($element->head_word->definition, Normalizer::FORM_D );
							$head_word_key = $word . '~~~' . $definition . '~~~' . $temp_language_id;
							//Log::error($head_word_key);
						
							if (array_key_exists($head_word_key,$stored_head_words)) {
								$new_element->head_word_id = $stored_head_words[$head_word_key];
							} else {
								$new_head_word = new EieolHeadWord;
								//fix bad data
								if ($word == '<dales, dalles, dallÄ‚Å >') {
									$word = '<dales, dalles, dallÃ©>';
								}
								$new_head_word->word = $word;
								$new_head_word->definition = $definition;
								$new_head_word->language_id = $temp_language_id;
								$new_head_word->created_by = 'loader';
								$new_head_word->updated_by = 'loader';
								$new_head_word->save();
								$stored_head_words[$head_word_key] = $new_head_word->id;
								$new_element->head_word_id = $new_head_word->id;
							}
						
							//Log::error($stored_head_words);
						
							$new_element->gloss_id = $new_gloss->id;
							$new_element->part_of_speech = $element->part_of_speech;
							if (array_key_exists('analysis',$element)) {
								$new_element->analysis = $element->analysis;
							}
							$new_element->order = $element->order;
							$new_element->created_by = 'loader';
							$new_element->updated_by = 'loader';
							$new_element->save();
						
						} //for each element
					}
					$new_glossed_text_gloss->glossed_text_id = $new_glossed_text->id;
					$new_glossed_text_gloss->order = $gloss->order * 10;
					$new_glossed_text_gloss->created_by = 'loader';
					$new_glossed_text_gloss->updated_by = 'loader';
					$new_glossed_text_gloss->save();
					
				} //for each gloss
			} //for each glossed_text
		} //if glossed_texts
		
		//load grammars
		if (array_key_exists('grammars',$lesson) ) {
			foreach ($lesson->grammars as $grammar){
				$new_grammar = new EieolGrammar;
				$new_grammar->lesson_id = $new_lesson->id;
				$new_grammar->title = Normalizer::normalize($grammar->title, Normalizer::FORM_D );
				$new_grammar->order = $grammar->order * 10;
				$new_grammar->section_number = $grammar->section_number;
				$new_grammar->grammar_text = Normalizer::normalize($grammar->grammar_text, Normalizer::FORM_D );				
				$new_grammar->lesson_id = $new_lesson->id;
				$new_grammar->created_by = 'loader';
				$new_grammar->updated_by = 'loader';
				$new_grammar->save();
			}
		} //if grammars
		
	}
	fclose($myfile);
} //store lessons

function build_serieses() {
	//some series have 2 languages:
	//Tocharian (Toch A 1-5, Toch B 6-10), 
	//Baltic(Lithuanian 1-7 Latvian 8-10), 
	//Albanian(Tosk 1-3 Geg 4-5), 
	//Iranian(Avestan 1-6 Old Persian 7-10)
	
	$serieses = array();
	
	$series = array();
	$series['series_id'] = 1;
	$series['language_id'] = 1;
	$series['path'] = '/var/www/html/app/storage/data_load/Latin Online.json';
	$series['index'] = array(1 => '/var/www/html/app/storage/data_load/indexes/latol-EI-X.json');
	$serieses[] = $series;
	
	$series = array();
	$series['series_id'] = 2;
	$series['language_id'] = 2;
	$series['path'] = '/var/www/html/app/storage/data_load/Classical Greek Online.json';
	$series['index'] = array(2 => '/var/www/html/app/storage/data_load/indexes/grkol-EI-X.json');
	$serieses[] = $series;

	$series = array();
	$series['series_id'] = 16;
	$series['language_id_0'] = 6;
	$series['language_id_1'] = 6;
	$series['language_id_2'] = 6;
	$series['language_id_3'] = 6;
	$series['language_id_4'] = 21;
	$series['language_id_5'] = 21;
	$series['path'] = '/var/www/html/app/storage/data_load/Albanian Online.json';
	$series['index'] = array(6 => '/var/www/html/app/storage/data_load/indexes/albol-EI-X.json', //Tosk 1-3
							 21 => '/var/www/html/app/storage/data_load/indexes/gegol-EI-X.json'); //Geg 4-5
	
	$serieses[] = $series;

	$series = array();
	$series['series_id'] = 10;
	$series['language_id'] = 7;
	$series['path'] = '/var/www/html/app/storage/data_load/Ancient Sanskrit Online.json';
	$series['index'] = array(7 => '/var/www/html/app/storage/data_load/indexes/vedol-EI-X.json');
	$serieses[] = $series;

	$series = array();
	$series['series_id'] = 8;
	$series['language_id_0'] = 8;
	$series['language_id_1'] = 8;
	$series['language_id_2'] = 8;
	$series['language_id_3'] = 8;
	$series['language_id_4'] = 8;
	$series['language_id_5'] = 8;
	$series['language_id_6'] = 8;
	$series['language_id_7'] = 8;
	$series['language_id_8'] = 20;
	$series['language_id_9'] = 20;
	$series['language_id_10'] = 20;
	$series['path'] = '/var/www/html/app/storage/data_load/Baltic Online.json';
	$series['index'] = array(8 => '/var/www/html/app/storage/data_load/indexes/litol-EI-X.json', //Lithuanian 1-7 
							20 => '/var/www/html/app/storage/data_load/indexes/lavol-EI-X.json'); //Latvian 8-10
	$serieses[] = $series;

	$series = array();
	$series['series_id'] = 5;
	$series['language_id'] = 9;
	$series['path'] = '/var/www/html/app/storage/data_load/Classical Armenian Online.json';
	$series['index'] = array(9 => '/var/www/html/app/storage/data_load/indexes/armol-EI-X.json');
	$serieses[] = $series;

	$series = array();
	$series['series_id'] = 19;
	$series['language_id'] = 24;
	$series['path'] = '/var/www/html/app/storage/data_load/Classical Armenian Online - Romanized.json';
	$series['index'] = array(24 => '/var/www/html/app/storage/data_load/indexes/armol-EI.json');
	$serieses[] = $series;

	$series = array();
	$series['series_id'] = 11;
	$series['language_id'] = 10;
	$series['path'] = '/var/www/html/app/storage/data_load/Gothic Online.json';
	$series['index'] = array(10 => '/var/www/html/app/storage/data_load/indexes/gotol-EI-X.json');
	$serieses[] = $series;

	$series = array();
	$series['series_id'] = 9;
	$series['language_id'] = 11;
	$series['path'] = '/var/www/html/app/storage/data_load/Hittite Online.json';
	$series['index'] = array(11 => '/var/www/html/app/storage/data_load/indexes/hitol-EI-X.json');
	$serieses[] = $series;

	$series = array();
	$series['series_id'] = 3;
	$series['language_id'] = 12;
	$series['path'] = '/var/www/html/app/storage/data_load/New Testament Greek Online.json';
	$series['index'] = array(12 => '/var/www/html/app/storage/data_load/indexes/ntgol-EI-X.json');
	$serieses[] = $series;

	$series = array();
	$series['series_id'] = 4;
	$series['language_id'] = 13;
	$series['path'] = '/var/www/html/app/storage/data_load/Old Church Slavonic Online.json';
	$series['index'] = array(13 => '/var/www/html/app/storage/data_load/indexes/ocsol-EI-X.json');
	$serieses[] = $series;

	$series = array();
	$series['series_id'] = 14;
	$series['language_id'] = 14;
	$series['path'] = '/var/www/html/app/storage/data_load/Old English Online.json';
	$series['index'] = array(14 => '/var/www/html/app/storage/data_load/indexes/engol-EI-X.json');
	$serieses[] = $series;

	$series = array();
	$series['series_id'] = 12;
	$series['language_id'] = 15;
	$series['path'] = '/var/www/html/app/storage/data_load/Old French Online.json';
	$series['index'] = array(15 => '/var/www/html/app/storage/data_load/indexes/ofrol-EI-X.json');
	$serieses[] = $series;

	$series = array();
	$series['series_id'] = 6;
	$series['language_id_0'] = 16;
	$series['language_id_1'] = 16;
	$series['language_id_2'] = 16;
	$series['language_id_3'] = 16;
	$series['language_id_4'] = 16;
	$series['language_id_5'] = 16;
	$series['language_id_6'] = 16;
	$series['language_id_7'] = 22;
	$series['language_id_8'] = 22;
	$series['language_id_9'] = 22;
	$series['language_id_10'] = 22;
	$series['path'] = '/var/www/html/app/storage/data_load/Old Iranian Online.json';
	$series['index'] = array(16 => '/var/www/html/app/storage/data_load/indexes/aveol-EI-X.json', //Avestan 1-6 
						22=> '/var/www/html/app/storage/data_load/indexes/opeol-EI-X.json'); //Old Persian 7-10
	$serieses[] = $series;

	$series = array();
	$series['series_id'] = 13;
	$series['language_id'] = 17;
	$series['path'] = '/var/www/html/app/storage/data_load/Old Irish Online.json';
	$series['index'] = array(17 => '/var/www/html/app/storage/data_load/indexes/iriol-EI-X.json');
	$serieses[] = $series;

	$series = array();
	$series['series_id'] = 7;
	$series['language_id'] = 18;
	$series['path'] = '/var/www/html/app/storage/data_load/Old Norse Online.json';
	$series['index'] = array(18 => '/var/www/html/app/storage/data_load/indexes/norol-EI-X.json');
	$serieses[] = $series;

	$series = array();
	$series['series_id'] = 17;
	$series['language_id'] = 19;
	$series['path'] = '/var/www/html/app/storage/data_load/Old Russian Online.json';
	$series['index'] = array(19 => '/var/www/html/app/storage/data_load/indexes/oruol-EI-X.json');
	$serieses[] = $series;

	$series = array();
	$series['series_id'] = 15;
	$series['language_id_0'] = 3;
	$series['language_id_1'] = 3;
	$series['language_id_2'] = 3;
	$series['language_id_3'] = 3;
	$series['language_id_4'] = 3;
	$series['language_id_5'] = 3;
	$series['language_id_6'] = 4;
	$series['language_id_7'] = 4;
	$series['language_id_8'] = 4;
	$series['language_id_9'] = 4;
	$series['language_id_10'] = 4;
	$series['language_id_11'] = 4;
	$series['language_id_20'] = 3;
	$series['path'] = '/var/www/html/app/storage/data_load/Tocharian Online.json';
	$series['index'] = array(3 => '/var/www/html/app/storage/data_load/indexes/tokol-EI-X.json', //Toch A 1-5
					4 =>  '/var/www/html/app/storage/data_load/indexes/txbol-EI-X.json'); //Toch B 6-10
	$serieses[] = $series;
	
	return $serieses;
}//build_serieses

class LoadController extends BaseController {	
	
	
	public function load()
	{
		return View::make('load.load');
	
	} //end load function
	
	public function eieol_delete()
	{	
		ini_set('memory_limit','512M');
		ini_set('max_execution_time', 500);
		
		Log::error('Starting eieol_delete on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
		
		$serieses = build_serieses();
	
		foreach($serieses as $series) {
			//print_series($series['series_id']);
			delete_series_children($series['series_id']);
		}
	
		print '<hr/>done';
		
		Log::error('Finishing eieol_delete on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
	} //end eieol_delete function

	public function eieol_load()
	{
		ini_set('memory_limit','512M');
		ini_set('max_execution_time', 1000);
		
		Log::error('Starting eieol_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
		
		$serieses = build_serieses();

// 		foreach($serieses as $series) {
// 			delete_series_children($series['series_id']);
// 		}
		
		foreach($serieses as $series) {
			print 'loading  ' . $series['path'] . '<br/>';
			store_lessons($series);
		}
		
		print '<hr/>done<br/>Now run index_load.';
		Log::error('Finishing eieol_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
	} //end eieol_load function
	
	public function index_load()
	{
		ini_set('memory_limit','320M');
		ini_set('max_execution_time', 500);
		
		Log::error('Starting index_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
		
		print 'this should only be run after the eieol_load';
	
		$serieses = build_serieses();
	
		foreach($serieses as $series) {
			print 'indexing  ' . $series['path'] . '<br/>';
			Log::error('indexing  ' . $series['path']);
			foreach($series['index'] as $lang => $url) {
				//if ($lang != 1) {
				//	continue;
				//}
				print $lang. ' ' . $url . '<br/>';
				$myfile = fopen($url, "r") or die("Unable to open file!");
				$data = json_decode(fread($myfile,filesize($url)));
				for($i = 0; $i<count($data); $i++) {
					$head_word = Normalizer::normalize($data[$i]->head_word, Normalizer::FORM_D );
					//hack to fix bad data
					if ($data[$i]->definition == 'strike ...against') {
						$data[$i]->definition = 'strike...against';
					}
					
					//print $data[$i]->keyword  . ' ' . htmlentities($head_word) . ' ' . $data[$i]->definition . '<br/>';
					//Log::error($data[$i]->keyword  . ' ' . $head_word . ' ' . $data[$i]->definition);
					
					$head_word_rec = EieolHeadWord::where('word', '=', $head_word)
												->where('definition', 'like',  $data[$i]->definition)
												->where('language_id', '=', $lang)->first();
					if ($head_word_rec == null) {
						//Log::error('trying 2nd lookup');
						$head_word_rec = EieolHeadWord::where('word', '=', $head_word)
						->where('definition', 'like',  '%' . $data[$i]->definition .  '%')
						->where('language_id', '=', $lang)->first();
					} 
					$new_head_word_keyword = new EieolHeadWordKeyword;
					$new_head_word_keyword->keyword = $data[$i]->keyword;
					$new_head_word_keyword->head_word_id = $head_word_rec['id'];
					$new_head_word_keyword->language_id = $lang;
					$new_head_word_keyword->created_by = 'loader';
					$new_head_word_keyword->updated_by = 'loader';
					
					try{
						$new_head_word_keyword->save();
					} catch (Exception $e) {
						echo 'Caught exception: ',  $e->getMessage(), "<br/>";
						print 'if the exception is for Tocharian OPINION, THOUGHT or THOUSAND, you can ignore this<br/>';
					}
				}
			}
		}
	
		print '<hr/>done';
		
		Log::error('Finishing index_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
	} //end index_load function
	
	public function element_count()
	{
		$glosses = EieolGloss::get();
		foreach($glosses as $gloss) {
			$count = EieolElement::where('gloss_id', '=', $gloss->id)->count();
			if ($count > 3) {
				print $gloss->id . ' ' . $count . '<br/>';
			}
		}
	
		print '<hr/>done';
	
	} //end element_count function
	
	public function gloss_sweep()
	{
		$glosses = EieolGloss::get();
		foreach($glosses as $gloss) {
			if (strpos($gloss->comments,'<font size="-1">') !== false) {
				print $gloss->surface_form . ' ' . $gloss->language_id . ' ' . $gloss->comments  . '<br/>';
				$gloss->comments = str_replace('<font size="-1">', '', $gloss->comments);
				$gloss->save();
			}
		}
	
		print '<hr/>done';
	
	} //end gloss_sweep function
	
	public function pos_analysis_load()
	{
		ini_set('memory_limit','320M');
		ini_set('max_execution_time', 500);
		
		Log::error('Starting pos_analysis_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
		
		$used_pos = array();
		$used_analysis = array();
		$elements = EieolElement::get();
		foreach($elements as $element) {
			print $element->part_of_speech . ' -- ' . $element->analysis . '<br/>';
			if (!in_array($element->part_of_speech, $used_pos)) {
				print 'add pos<br/>';
				$used_pos[] = $element->part_of_speech;
				$part_of_speech = new EiEOLPartOfSpeech;
				$part_of_speech->part_of_speech = $element->part_of_speech ;
				$part_of_speech->created_by = 'loader';
				$part_of_speech->updated_by = 'loader';
				$part_of_speech->save();
			}
			if ($element->analysis != null && !in_array($element->analysis, $used_analysis)) {
				print 'add analysis<br/>';
				$used_analysis[] = $element->analysis;
				$analysis = new EieolAnalysis;
				$analysis->analysis = $element->analysis ;
				$analysis->created_by = 'loader';
				$analysis->updated_by = 'loader';
				$analysis->save();
			}
		}
		print_r($used_pos);
		print '<hr/>';
		print_r($used_analysis);
		print '<hr/>done';
		Log::error('Finishing pos_analysis_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
	} //end pos_analysis_load function

	
	public function lex_sources_load()
	{	
		Log::error('Starting lex_sources_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
		$url = '/var/www/html/app/storage/data_load/lex_sources.json';
		$myfile = fopen($url, "r") or die("Unable to open file!");
		$data = json_decode(fread($myfile,filesize($url)));
		foreach ($data as $key => $value) {
			print $key . ' ' . $value . '<br/>';
			$lex_source = new LexSource;
			$lex_source->code = $key;
			$lex_source->display = $value;
			$lex_source->created_by = 'loader';
			$lex_source->updated_by = 'loader';
			$lex_source->save();
		}

		print '<hr/>done';
		Log::error('Finishing lex_sources_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
	} //end lex_sources_load function
	
	public function lex_pos_load()
	{
		Log::error('Starting lex_pos_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
		$url = '/var/www/html/app/storage/data_load/lex_parts_of_speech.json';
		$myfile = fopen($url, "r") or die("Unable to open file!");
		$data = json_decode(fread($myfile,filesize($url)));
		foreach ($data as $key => $value) {
			print $key . ' ' . $value . '<br/>';
			$lex_part_of_speech = new LexPartOfSpeech;
			$lex_part_of_speech->code = $key;
			$lex_part_of_speech->display = $value;
			$lex_part_of_speech->created_by = 'loader';
			$lex_part_of_speech->updated_by = 'loader';
			$lex_part_of_speech->save();
		}
	
		print '<hr/>done';
		Log::error('Finishing lex_pos_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
	} //end lex_pos_load function
	
	public function lex_lang_load()
	{
		Log::error('Starting lex_lang_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
		$url = '/var/www/html/app/storage/data_load/lex_langs.json';
		$myfile = fopen($url, "r") or die("Unable to open file!");
		$data = json_decode(fread($myfile,filesize($url)));
		
		$baltic_langs = array('Nadr','Skal','Galin','Sudo','OPrus','Curo','Sel','Zem','OLith','LLith','Lith','Latv');
		$slavic_langs = array('OSlav','OPol','Pol','OPom','Kashub','Polab','OCz','Cz','Knaan','Slovak','OSorb','Wend','Sorb','USorb','LSorb','Lusat','OSerb','SCr','Serb','Croat','Bosn','Slovene','OCS','LCS','Bulg','MacSl','ORuss','RCS','Russ','Ruth','Belo','Ukr');
		$albanian_langs = array('Alb','Gheg','Tosk','Arv','Arb');
		$iranian_langs = array('OIran','MIran','Yas','Iran','OPers','Med','Pahl','MPers','Mani','Parth','NPers','Pers','Farsi','Balu','Kurd',
							   'Par','Dari','Taj','OAv','Av','YAv','OScyth','Scyth','Sogd','Bact','Chor','Alan','OOss','Khot','Sac','Saka','Tums',
							   'Oss',' Push','Yagn','Pamir','Shug','Sari','Yazg','Munj','Ishk','Sang','Wakh','Yidg','Wane','Pras','Bash','Ashk',
							   'Treg','Waig','Khow','Kals','Kash','Kish','Pogu','Bat','Chil','Gow','Kalk','Kalm','Koh','Tira','Torw','Wota',
							   'Dam','Gawar','Nang','Shum','Pash','PashNW','PashSW','PashNE','PashSE','Brok','Domk','Phal','ShinKo','Shina',
							   'Savi','Ush');
		$indic_langs = array('RV','Ved','Skt','Prak','Gand','Pali','Saur','OMart','Assm','Beng','Bhil','Bih','Domr','Guj','Hin','Khan','Konk',
							 'Kum','Lahn','Mald','Mart','Nep','Ori','Pnj','Raj','Rmy','Sind','Sinh','Thar','Urdu');
		
		for($i = 0; $i<count($data); $i++) {
			print $data[$i]->name . ' ' . $data[$i]->order . '<br/>';
			$language_family = new LexLanguageFamily;
			$language_family->name = $data[$i]->name;
			$language_family->order = $data[$i]->order;
			$language_family->created_by = 'loader';
			$language_family->updated_by = 'loader';
			$language_family->save();
			
			$subfamilies = $data[$i]->subfamilies;
			for($j = 0; $j<count($subfamilies); $j++) {
				print '...' . $subfamilies[$j]->name . ' ' . $subfamilies[$j]->order . '<br/>';
				$language_sub_family = new LexLanguageSubFamily;
				$language_sub_family->name = $subfamilies[$j]->name;
				$language_sub_family->order = $subfamilies[$j]->order;
				$language_sub_family->family_id = $language_family->id;
				$language_sub_family->created_by = 'loader';
				$language_sub_family->updated_by = 'loader';
				$language_sub_family->save();
				
				$languages = $subfamilies[$j]->languages;
				for($k = 0; $k<count($languages); $k++) {
					print '......' . $languages[$k]->name . ' ' . $languages[$k]->order . ' ' . $languages[$k]->abbr . ' ' . $languages[$k]->aka. '<br/>';
					
					
					$language = new LexLanguage;
					$language->name = $languages[$k]->name;
					$language->order = $languages[$k]->order;
					$language->abbr = $languages[$k]->abbr;
					
					if(in_array($languages[$k]->abbr,$baltic_langs)) {
						$language->override_family = 'Baltic';
					}
					if(in_array($languages[$k]->abbr,$slavic_langs)) {
						$language->override_family = 'Slavic';
					}
					if(in_array($languages[$k]->abbr,$albanian_langs)) {
						$language->override_family = 'Albanian';
					}
					if(in_array($languages[$k]->abbr,$iranian_langs)) {
						$language->override_family = 'Iranian';
					}
					if(in_array($languages[$k]->abbr,$indic_langs)) {
						$language->override_family = 'Indic';
					}
					
					$language->aka = $languages[$k]->aka;
					$language->sub_family_id = $language_sub_family->id;
					$language->created_by = 'loader';
					$language->updated_by = 'loader';
					$language->save();
				}
			}
		}
		
		print '<hr/>done';
		Log::error('Finishing lex_lang_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
	} //end lex_lang_load function
	
	public function lex_sem_load()
	{
		Log::error('Starting lex_sem_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
		$url = '/var/www/html/app/storage/data_load/lex_categories.json';
		$myfile = fopen($url, "r") or die("Unable to open file!");
		$data = json_decode(fread($myfile,filesize($url)));
	
		for($i = 0; $i<count($data); $i++) {
			print $data[$i]->text . ' ' . $data[$i]->number  .' ' . $data[$i]->abbr . '<br/>';
			$semantic_category = new LexSemanticCategory;
			$semantic_category->text = $data[$i]->text;
			$semantic_category->number = $data[$i]->number;
			$semantic_category->abbr = $data[$i]->abbr;
			$semantic_category->created_by = 'loader';
			$semantic_category->updated_by = 'loader';
			$semantic_category->save();
				
			$fields = $data[$i]->fields;
			for($j = 0; $j<count($fields); $j++) {
				print '...' . $fields[$j]->text . ' ' . $fields[$j]->number . ' ' . $fields[$j]->abbr . '<br/>';
				$semantic_field = new LexSemanticField;
				$semantic_field->text = $fields[$j]->text;
				$semantic_field->number = $fields[$j]->number;
				$semantic_field->abbr = $fields[$j]->abbr;
				$semantic_field->semantic_category_id = $semantic_category->id;
				$semantic_field->created_by = 'loader';
				$semantic_field->updated_by = 'loader';
				$semantic_field->save();
			}
		}
	
		print '<hr/>done';
		Log::error('Finishing lex_sem_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
	} //end lex_sem_load function
	
	public function lex_load()
	{
		ini_set('memory_limit','3000M');
		ini_set('max_execution_time', 20000);
		
		Log::error('Starting lex_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
		
		$sems = array();
		$semantics = LexSemanticField::with('semantic_category')->get();
		foreach($semantics as $semantic) {
			$sems[$semantic->semantic_category->abbr . '/' . $semantic->abbr] = $semantic->id;
		}
		//print_r($sems);
		
		$srcs = array();
		$sources = LexSource::all();
		foreach($sources as $source) {
			$srcs[$source->code] = $source->id;
		}
		//print_r($srcs);
		
		$poss = array();
		$parts_of_speech = LexPartOfSpeech::all();
		foreach($parts_of_speech as $part_of_speech) {
			$poss[$part_of_speech->code] = $part_of_speech->id;
		}
		//print_r($poss);
		
		$langs = array();
		$languages = LexLanguage::all();
		foreach($languages as $language) {
			$langs[$language->abbr] = $language->id;
		}
		//print_r($langs);
		
		$used_reflexes = array();
		

 		$url = '/var/www/html/app/storage/data_load/lex_etymas.json';
 		$myfile = fopen($url, "r") or die("Unable to open file!");
 		$data = json_decode(fread($myfile,filesize($url)));
		
 		for($i = 0; $i<count($data); $i++) {
 			print $data[$i]->old_id . ' ' . $data[$i]->entry  .' ' . $data[$i]->gloss . ' ' . $data[$i]->page_number . '<br/>';
 			$etyma = new LexEtyma;
 			$etyma->old_id = $data[$i]->old_id;
 			$etyma->order = $data[$i]->old_id * 10;
 			$etyma->page_number = $data[$i]->page_number;
 			$etyma->entry = Normalizer::normalize($data[$i]->entry, Normalizer::FORM_D );
 			$etyma->gloss = $data[$i]->gloss;
 			$etyma->created_by = 'loader';
 			$etyma->updated_by = 'loader';
 			$etyma->save();
 			
 			$semantics = $data[$i]->semantics;
 			for($j = 0; $j<count($semantics); $j++) {
 				$abbr =$semantics[$j];
 				print '...' . $abbr . '=' . $sems[$abbr] . '<br/>';
 				$etyma_semantic_field = new LexEtymaSemanticField;
 				$etyma_semantic_field->etyma_id = $etyma->id;
 				$etyma_semantic_field->semantic_field_id = $sems[$abbr];
 				$etyma_semantic_field->created_by = 'loader';
 				$etyma_semantic_field->updated_by = 'loader';
 				$etyma_semantic_field->save();
 			}
 			$reflexes = $data[$i]->reflexes;
 			for($j = 0; $j<count($reflexes); $j++) {
 				print '___' . implode(',',$reflexes[$j]->entries) . ' ' . $reflexes[$j]->language . ' ' . $reflexes[$j]->gloss . ' ' . $reflexes[$j]->part_of_speech . ' ' . $reflexes[$j]->source . ' ' . $reflexes[$j]->lang_attribute . ' ' . $reflexes[$j]->class_attribute . '<br/>';
 				$key = $reflexes[$j]->language . '~~~' . implode(',',$reflexes[$j]->entries) . '~~~' . $reflexes[$j]->part_of_speech . '~~~' . $reflexes[$j]->gloss . '~~~' . $reflexes[$j]->source;
 				if (array_key_exists($key, $used_reflexes)) {
 					print '****got it-' . $used_reflexes[$key] . '<br/>';
 					$hold_reflex_id = $used_reflexes[$key];
 				} else {
 					$reflex = new LexReflex;
 					$reflex->language_id = $langs[$reflexes[$j]->language];
 					$reflex->lang_attribute = $reflexes[$j]->lang_attribute;
 					$reflex->class_attribute = $reflexes[$j]->class_attribute;
 					$reflex->gloss = $reflexes[$j]->gloss;
 					$reflex->created_by = 'loader';
 					$reflex->updated_by = 'loader';
 					$reflex->save();
 					$used_reflexes[$key] = $reflex->id;
 					$hold_reflex_id = $reflex->id;
 					
 					$entry_ctr = 0;
 					for($k = 0; $k<count($reflexes[$j]->entries); $k++) {
 						$entry_ctr += 1;
 						$reflex_entry = new LexReflexEntry;
 						$reflex_entry->reflex_id = $hold_reflex_id;
 						$reflex_entry->entry = Normalizer::normalize(trim($reflexes[$j]->entries[$k]), Normalizer::FORM_D );
 						$reflex_entry->order = $entry_ctr * 10;
 						$reflex_entry->created_by = 'loader';
 						$reflex_entry->updated_by = 'loader';
 						$reflex_entry->save();
 					}
 					
 					$reflexes[$j]->part_of_speech = str_replace(';', '/', $reflexes[$j]->part_of_speech);
 					$load_pos=explode("/",$reflexes[$j]->part_of_speech);
 					$pos_ctr = 0;
 					for($k = 0; $k<count($load_pos); $k++) {
 						$pos_ctr += 1; 						
 						$reflex_part_of_speech = new LexReflexPartOfSpeech;
 						$reflex_part_of_speech->reflex_id = $reflex->id;
 						$reflex_part_of_speech->text = $load_pos[$k];
 						$reflex_part_of_speech->order = $pos_ctr * 10;
 						$reflex_part_of_speech->created_by = 'loader';
 						$reflex_part_of_speech->updated_by = 'loader';
 						$reflex_part_of_speech->save();
 					}
 					
 					$load_source=explode("/",$reflexes[$j]->source);
 					$source_ctr = 0;
 					for($k = 0; $k<count($load_source); $k++) {
 						$source_ctr += 1;
 						$temp_source = $load_source[$k];
 						if ($temp_source == '' || $temp_source == ' ' || $temp_source == null ) {
 							break;
 						}
 						$reflex_source = new LexReflexSource;
 						$reflex_source->reflex_id = $reflex->id;
 						$reflex_source->source_id = $srcs[$temp_source];
 						$reflex_source->order = $source_ctr * 10;
 						$reflex_source->created_by = 'loader';
 						$reflex_source->updated_by = 'loader';
 						$reflex_source->save();
 					}
 				}
 				$etyma_reflex = new LexEtymaReflex;
 				$etyma_reflex->etyma_id = $etyma->id;
 				$etyma_reflex->reflex_id = $hold_reflex_id;
 				$etyma_reflex->created_by = 'loader';
 				$etyma_reflex->updated_by = 'loader';
 				$etyma_reflex->save();
 			}
 		}
	
		print '<hr/>done';
		Log::error('Finishing lex_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
	} //end lex_load function
	
	public function lex_cross_load()
	{
		ini_set('memory_limit','512M');
		ini_set('max_execution_time', 1000);
	
		Log::error('Starting lex_cross_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
		$ets = array();
		$etymas = LexEtyma::all();
		foreach($etymas as $etyma) {
			$ets[$etyma->old_id] = $etyma->id;
		}
		//print_r($ets);	
	
		$url = '/var/www/html/app/storage/data_load/lex_etymas.json';
		$myfile = fopen($url, "r") or die("Unable to open file!");
		$data = json_decode(fread($myfile,filesize($url)));
	
		for($i = 0; $i<count($data); $i++) {
			print $data[$i]->old_id . ' ' . $data[$i]->entry  . '<br/>';
			$from_id = $data[$i]->old_id;
			$crosses = $data[$i]->cross;
			for($j = 0; $j<count($crosses); $j++) {
				print '...' . $crosses[$j] . '<br/>';
				$to_id = $crosses[$j];
				$etyma_cross_reference = new LexEtymaCrossReference;
				$etyma_cross_reference->from_etyma_id = $ets[$from_id];
				$etyma_cross_reference->to_etyma_id = $ets[$to_id];
				$etyma_cross_reference->created_by = 'loader';
				$etyma_cross_reference->updated_by = 'loader';
				$etyma_cross_reference->save();
			}
		}
	
		print '<hr/>done';
		Log::error('Finishing lex_cross_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	} //end lex_cross_load function
	
	
	public function sem_etyma_load()
	{
		Log::error('Starting sem_etyma_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
		
		$sems = array();
		$semantic_fields = LexSemanticField::all();
		foreach($semantic_fields as $semantic_field) {
			$sems[$semantic_field->abbr] = $semantic_field->id;
		}
		
		$ets = array();
		$etymas = LexEtyma::all();
		foreach($etymas as $etyma) {
			$ets[$etyma->old_id] = $etyma->id;
		}
		
		$url = '/var/www/html/app/storage/data_load/lex_sem_etymas.json';
		$myfile = fopen($url, "r") or die("Unable to open file!");
		$data = json_decode(fread($myfile,filesize($url)));
		
		for($i = 0; $i<count($data); $i++) {
			$et = $data[$i]->etyma;
			$sem = $data[$i]->sem;
			print $sem . ' ' . $sems[$sem] . ' ' . $et  . ' ' . $ets[$et] . '<br/>';
			
			$etyma_semantic_field = new LexEtymaSemanticField;
			$etyma_semantic_field->etyma_id = $ets[$et];
			$etyma_semantic_field->semantic_field_id = $sems[$sem];
			$etyma_semantic_field->created_by = 'loader';
			$etyma_semantic_field->updated_by = 'loader';
			$etyma_semantic_field->save();
		}		
			
		print '<hr/>done';
		Log::error('Finishing sem_etyma_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
	} //end sem_etyma_load function
	
	public function default_alpha()
	{
		Log::error('Starting default_alpha on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
		
		$languages = LexLanguage::get();
		foreach($languages as $language) {
			$language->custom_sort = 'aAàǣ,ā,bB,cC,dD,eEēÉe̐,é,fF,gG,hH,iIīí,jJ,kK,lL,mM,nN,oOò,ō,ð,pP,qQ,rR,sS,tT,uUúū,vV,wW,xX,yY,zZ';
			$language->save();
		}
			
		print '<hr/>done';
		Log::error('Finishing sem_etyma_load on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
	} //end default_alpha function
	
	public function link_headword_to_eytma()
	{
		Log::error('Starting link_headword_to_eytma on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
		$ets = array();
		$etymas = LexEtyma::get();
		foreach($etymas as $etyma) {
			$ets[$etyma->old_id] = $etyma->id;
		}
		//print_r($ets);
		
		$url = '/var/www/html/app/storage/data_load/eieol_lex_links.json';
		$myfile = fopen($url, "r") or die("Unable to open file!");
		$data = json_decode(fread($myfile,filesize($url)));
		
		for($i = 0; $i<count($data); $i++) {
			try{
				$head_word = EieolHeadWord::where('word', '=', '<' . Normalizer::normalize($data[$i]->word, Normalizer::FORM_D ) . '>')
											->where('definition', 'like',  '%' . $data[$i]->definition .  '%')
											->where('language_id', '=', $data[$i]->language_id)
											->get()[0];
				$head_word->etyma_id = $ets[$data[$i]->old_etyma_id];
				$head_word->save();
			} catch (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "<br/>";
				print_r($data[$i]);
				print '<hr/>';
			}
			#print $head_word . '<br/>';		
		}
			
		print '<hr/>done<hr/>';
		print 'WARNING:  If there are anyexeptions, go add them by hand in phpmyadmin';
		Log::error('Finishing link_headword_to_eytma on ' . gethostname() . ' at ' . date("D M d, Y G:i a"));
	
	} //end link_headword_to_eytma function
	
	public function paren_count()
	{
		ini_set('memory_limit','3000M');
		ini_set('max_execution_time', 20000);
		
		$entries = LexReflexEntry::get();
		foreach($entries as $entry) {
			if (mb_strpos($entry->entry,'(', 0,'UTF-8') !== False) {
				$parts = array();
				$open = mb_strpos($entry->entry,'(', 0,'UTF-8');
				$close = mb_strpos($entry->entry,')', 0,'UTF-8');
				
				$parts[] = mb_substr($entry->entry, 0, $open, 'UTF-8');
	
				$len = $close - $open;//if a reflex contains characters in (), split into 2, ex (g)nosco = gnosco and nosco
				$parts[] = mb_substr($entry->entry, $open + 1, $len - 1, 'UTF-8');
						
				$len = mb_strlen($entry, 'UTF-8') - $close;
				$parts[] = mb_substr($entry->entry, $close + 1, $len, 'UTF-8');
				foreach($parts as $part){
					if (mb_strpos($part,'(', 0,'UTF-8') !== False) {
						print $entry->id . ' ' . $entry->entry . '<br/>';
					}
				}
			}
		}
	
		print '<hr/>done';
	
	} //end paren_count function
	
} //end load controller