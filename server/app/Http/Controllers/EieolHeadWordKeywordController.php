<?php

namespace App\Http\Controllers;

use App\EieolHeadWordKeyword;
use Illuminate\Http\Request;

class EieolHeadWordKeywordController extends Controller {
	
	public function filtered_list(Request $request)
	{
		//this returns an array of all keywords for use by autocomplete
		$array = array();
		$keywords = EieolHeadWordKeyword::where('keyword', 'LIKE', '%' . $request->get('term') . '%')
										->where('language_id', '=', $request->get('language') . '%')
										->take(25)->groupby('keyword')->get(['keyword']);
		foreach ($keywords as $keyword) {
			$array[] = $keyword->keyword;		
		}
		return $array;
	}
	
}
