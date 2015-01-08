<?php

class EieolHeadWordKeywordController extends BaseController {	
	
	public function filtered_list()
	{
		//this returns an array of all keywords for use by autocomplete
		$array = array();
		$keywords = EieolHeadWordKeyword::where('keyword', 'LIKE', '%' . Input::get('term') . '%')
										->where('language_id', '=', Input::get('language') . '%')
										->take(25)->groupby('keyword')->get();
		foreach ($keywords as $keyword) {
			$array[] = $keyword->keyword;		
		}
		return $array;
	}
	
}