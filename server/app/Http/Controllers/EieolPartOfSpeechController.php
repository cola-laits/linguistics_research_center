<?php

namespace App\Http\Controllers;

use App\EieolPartOfSpeech;
use Illuminate\Http\Request;

class EieolPartOfSpeechController extends Controller {
	
	public function filtered_list(Request $request): array {
		//this returns an array of all parts of speech for use by autocomplete
		$array = array();
		$parts_of_speech = EieolPartOfSpeech::where('part_of_speech', 'LIKE', '%' . $request->get('term') . '%')
										    ->where('language_id', '=', $request->get('language_id'))
											->take(25)
											->groupby('part_of_speech')
											->get(['part_of_speech']);
		foreach ($parts_of_speech as $part_of_speech) {
			$array[] = $part_of_speech->part_of_speech;		
		}
		return $array;
	}
	
}
