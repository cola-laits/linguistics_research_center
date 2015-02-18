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
	for($i = 0; $i<count($data); $i++) {
		print $i . ' ' . $data[$i]->title . '<br/>';
		$lesson = new EieolLesson;
		$lesson->title = $data[$i]->title;
		$lesson->order = $data[$i]->order * 10;
		$lesson->series_id = $series['series_id'];
		if (array_key_exists('language_id',$series) ) {
			$lesson->language_id = $series['language_id'];
		} else {
			$lesson->language_id = $series['language_id_' . (string) $data[$i]->order];
		}
		$lesson->intro_text = $data[$i]->lesson_intro;
		$lesson->lesson_translation = $data[$i]->lesson_translation;
		$lesson->created_by = 'loader';
		$lesson->updated_by = 'loader';
		$lesson->save();
	}
	fclose($myfile);
} //store lessons

class LoadController extends BaseController {	

	public function eieol_load()
	{
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
		$series['path'] = '/var/www/html/app/storage/data_load/Tocharian Online.json';
		$serieses[] = $series;
		
		foreach($serieses as $series) {
			print_series($series['series_id']);
			delete_series_children($series['series_id']);
			store_lessons($series);
		}
	
	} //end eieol_load function
	
} //end load controller