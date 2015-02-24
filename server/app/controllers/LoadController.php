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
					$new_gloss = new EieolGloss;
					$new_gloss->surface_form = Normalizer::normalize($gloss->surface_form, Normalizer::FORM_C );
					$new_gloss->contextual_gloss = Normalizer::normalize($gloss->contextual_gloss, Normalizer::FORM_C );
					if (array_key_exists('comments',$gloss) ) {
						$new_gloss->comments = Normalizer::normalize($gloss->comments, Normalizer::FORM_C );
					}
					$new_gloss->language_id = $temp_language_id;
					$new_gloss->created_by = 'loader';
					$new_gloss->updated_by = 'loader';
					$new_gloss->save();
					
					$new_glossed_text_gloss = new EieolGlossedTextGloss;
					$new_glossed_text_gloss->glossed_text_id = $new_glossed_text->id;
					$new_glossed_text_gloss->gloss_id = $new_gloss->id;
					$new_glossed_text_gloss->order = $gloss->order * 10;
					$new_glossed_text_gloss->save();
				}
			}
		} //if grammars
		
		//load grammars
		if (array_key_exists('grammars',$lesson) ) {
			foreach ($lesson->grammars as $grammar){
				$new_grammar = new EieolGrammar;
				$new_grammar->lesson_id = $new_lesson->id;
				$new_grammar->title = Normalizer::normalize($grammar->title, Normalizer::FORM_C );
				$new_grammar->order = $grammar->order * 10;
				$new_grammar->section_number = $grammar->section_number;
				$new_grammar->grammar_text = Normalizer::normalize($grammar->grammar_text, Normalizer::FORM_C );				$new_grammar->lesson_id = $new_lesson->id;
				$new_grammar->created_by = 'loader';
				$new_grammar->updated_by = 'loader';
				$new_grammar->save();
			}
		} //if grammars
		
	}
	fclose($myfile);
} //store lessons

class LoadController extends BaseController {	

	public function eieol_load()
	{
		ini_set('memory_limit','256M');
		ini_set('max_execution_time', 300);
		
		//some series have 2 languages:
		//Tocharian (Toch A 1-5, Toch B 6-10), Baltic(Lithuanian 1-7 Latvian 8-10), Albanian(Tosk 1-3 Geg 4-5), Iranian(Old Avestan 1-4 Young Avestan 5-6 Old Persian 7-10)
	
		$serieses = array();
		
		$series = array();
		$series['series_id'] = 1;
		$series['language_id'] = 1;
		$series['path'] = '/var/www/html/app/storage/data_load/Latin Online.json';
		$serieses[] = $series;
		
		$series = array();
		$series['series_id'] = 2;
		$series['language_id'] = 2;
		$series['path'] = '/var/www/html/app/storage/data_load/Classical Greek Online.json';
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
		$serieses[] = $series;
		
		$series = array();
		$series['series_id'] = 10;
		$series['language_id'] = 7;
		$series['path'] = '/var/www/html/app/storage/data_load/Ancient Sanskrit Online.json';
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
		$serieses[] = $series;
		
		$series = array();
		$series['series_id'] = 5;
		$series['language_id'] = 9;
		$series['path'] = '/var/www/html/app/storage/data_load/Classical Armenian Online.json';
		$serieses[] = $series;
		
		$series = array();
		$series['series_id'] = 19;
		$series['language_id'] = 9;
		$series['path'] = '/var/www/html/app/storage/data_load/Classical Armenian Online - Romanized.json';
		$serieses[] = $series;
		
		$series = array();
		$series['series_id'] = 11;
		$series['language_id'] = 10;
		$series['path'] = '/var/www/html/app/storage/data_load/Gothic Online.json';
		$serieses[] = $series;
		
		$series = array();
		$series['series_id'] = 9;
		$series['language_id'] = 11;
		$series['path'] = '/var/www/html/app/storage/data_load/Hittite Online.json';
		$serieses[] = $series;
		
		$series = array();
		$series['series_id'] = 3;
		$series['language_id'] = 12;
		$series['path'] = '/var/www/html/app/storage/data_load/New Testament Greek Online.json';
		$serieses[] = $series;
		
		$series = array();
		$series['series_id'] = 4;
		$series['language_id'] = 13;
		$series['path'] = '/var/www/html/app/storage/data_load/Old Church Slavonic Online.json';
		$serieses[] = $series;
		
		$series = array();
		$series['series_id'] = 14;
		$series['language_id'] = 14;
		$series['path'] = '/var/www/html/app/storage/data_load/Old English Online.json';
		$serieses[] = $series;
		
		$series = array();
		$series['series_id'] = 12;
		$series['language_id'] = 15;
		$series['path'] = '/var/www/html/app/storage/data_load/Old French Online.json';
		$serieses[] = $series;
		
		$series = array();
		$series['series_id'] = 6;
		$series['language_id_0'] = 16;
		$series['language_id_1'] = 16;
		$series['language_id_2'] = 16;
		$series['language_id_3'] = 16;
		$series['language_id_4'] = 16;
		$series['language_id_5'] = 23;
		$series['language_id_6'] = 23;
		$series['language_id_7'] = 22;
		$series['language_id_8'] = 22;
		$series['language_id_9'] = 22;
		$series['language_id_10'] = 22;
		$series['path'] = '/var/www/html/app/storage/data_load/Old Iranian Online.json';
		$serieses[] = $series;
		
		$series = array();
		$series['series_id'] = 13;
		$series['language_id'] = 17;
		$series['path'] = '/var/www/html/app/storage/data_load/Old Irish Online.json';
		$serieses[] = $series;
		
		$series = array();
		$series['series_id'] = 7;
		$series['language_id'] = 18;
		$series['path'] = '/var/www/html/app/storage/data_load/Old Norse Online.json';
		$serieses[] = $series;
		
		$series = array();
		$series['series_id'] = 17;
		$series['language_id'] = 19;
		$series['path'] = '/var/www/html/app/storage/data_load/Old Russian Online.json';
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
		$serieses[] = $series;
		
		foreach($serieses as $series) {
			//print_series($series['series_id']);
			delete_series_children($series['series_id']);
			store_lessons($series);
		}
		
		print '<hr/>done';
	
	} //end eieol_load function
	
} //end load controller