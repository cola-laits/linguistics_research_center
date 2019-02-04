<?php

namespace App\Http\Controllers;

use App\EieolAnalysis;
use Illuminate\Http\Request;

class EieolAnalysisController extends Controller {
	
	public function filtered_list(Request $request)
	{
		//this returns an array of all analysis for use by autocomplete
		$array = array();
		$analysises = EieolAnalysis::where('analysis', 'LIKE', '%' . $request->get('term') . '%')
								   ->where('language_id', '=', $request->get('language_id'))
								   ->take(25)
								   ->groupby('analysis')
								   ->get(['analysis']);
		foreach ($analysises as $analysis) {
			$array[] = $analysis->analysis;		
		}
		return $array;
	}
	
}
