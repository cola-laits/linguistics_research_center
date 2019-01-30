<?php

namespace App\Http\Controllers;

class EieolAnalysisController extends Controller {
	
	public function filtered_list()
	{
		//this returns an array of all analysis for use by autocomplete
		$array = array();
		$analysises = EieolAnalysis::where('analysis', 'LIKE', '%' . Input::get('term') . '%')
								   ->where('language_id', '=', Input::get('language_id'))
								   ->take(25)
								   ->groupby('analysis')
								   ->get();
		foreach ($analysises as $analysis) {
			$array[] = $analysis->analysis;		
		}
		return $array;
	}
	
}
