<?php

class EieolPartOfSpeechController extends BaseController {	
	
	public function filtered_list()
	{
		//this returns an array of all parts of speech for use by autocomplete
		$array = array();
		$parts_of_speech = EieolPartOfSpeech::where('part_of_speech', 'LIKE', '%' . Input::get('term') . '%')->take(25)->groupby('part_of_speech')->get();
		foreach ($parts_of_speech as $part_of_speech) {
			$array[] = $part_of_speech->part_of_speech;		
		}
		return $array;
	}
	
}