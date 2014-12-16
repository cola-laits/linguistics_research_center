<?php

class EieolAnalysisController extends BaseController {	
	
	public function filtered_list()
	{
		//this returns an array of all analysis for use by autocomplete
		$array = array();
		$analysises = EieolAnalysis::where('analysis', 'LIKE', '%' . Input::get('term') . '%')->take(25)->groupby('analysis')->get();
		foreach ($analysises as $analysis) {
			$array[] = $analysis->analysis;		
		}
		return $array;
	}
	
}