<?php

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
		$new_lesson->title =  Normalizer::normalize($lesson->title, Normalizer::FORM_C );
		$new_lesson->order = $lesson->order * 10;
		$new_lesson->series_id = $series['series_id'];
		if (array_key_exists('language_id',$series) ) {
			$temp_language_id = $series['language_id'];
		} else {
			$temp_language_id = $series['language_id_' . (string) $lesson->order];
		}
		$new_lesson->language_id = $temp_language_id;
		$new_lesson->intro_text = Normalizer::normalize($lesson->intro_text, Normalizer::FORM_C );
		$new_lesson->lesson_translation =  Normalizer::normalize($lesson->lesson_translation, Normalizer::FORM_C );
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
				$new_glossed_text->glossed_text = Normalizer::normalize($glossed_text->glossed_text, Normalizer::FORM_C );
				$new_glossed_text->order = $glossed_text->order * 10;
				$new_glossed_text->created_by = 'loader';
				$new_glossed_text->updated_by = 'loader';
				$new_glossed_text->save();
				
				foreach ($glossed_text->glosses as $gloss) {
					$new_glossed_text_gloss = new EieolGlossedTextGloss;
					$surface_form = Normalizer::normalize($gloss->surface_form, Normalizer::FORM_C );
					$part_of_speech = $gloss->elements[0]->part_of_speech;
					$contextual_gloss = Normalizer::normalize($gloss->contextual_gloss, Normalizer::FORM_C );
					
					if (array_key_exists('analysis',$gloss->elements[0])) {
						$analysis = $gloss->elements[0]->analysis;
					} else {
						$analysis = '';
					}
					
					//get first headword
					foreach ($gloss->elements as $element) {
						$head_word = Normalizer::normalize($element->head_word->word, Normalizer::FORM_C );
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
							$new_gloss->comments = Normalizer::normalize($gloss->comments, Normalizer::FORM_C );
						}
						$new_gloss->language_id = $temp_language_id;
						$new_gloss->created_by = 'loader';
						$new_gloss->updated_by = 'loader';
						$new_gloss->save();
						$stored_glosses[$gloss_key] = $new_gloss->id;
						$new_glossed_text_gloss->gloss_id = $new_gloss->id;
						
						foreach ($gloss->elements as $element) {
							$new_element = new EieolElement;
						
							$word = Normalizer::normalize($element->head_word->word, Normalizer::FORM_C );
							$definition = Normalizer::normalize($element->head_word->definition, Normalizer::FORM_C );
							$head_word_key = $word . '~~~' . $definition . '~~~' . $temp_language_id;
							//Log::error($head_word_key);
						
							if (array_key_exists($head_word_key,$stored_head_words)) {
								$new_element->head_word_id = $stored_head_words[$head_word_key];
							} else {
								$new_head_word = new EieolHeadWord;
								//fix bad data
								if ($word == '<dales, dalles, dallĂŠ>') {
									$word = '<dales, dalles, dallé>';
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
				$new_grammar->title = Normalizer::normalize($grammar->title, Normalizer::FORM_C );
				$new_grammar->order = $grammar->order * 10;
				$new_grammar->section_number = $grammar->section_number;
				$new_grammar->grammar_text = Normalizer::normalize($grammar->grammar_text, Normalizer::FORM_C );				
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
					$head_word = Normalizer::normalize($data[$i]->head_word, Normalizer::FORM_C );
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
				$part_of_speech = new PartOfSpeech;
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
	
	
	
} //end load controller