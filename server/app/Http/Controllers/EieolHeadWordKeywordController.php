<?php

namespace App\Http\Controllers;

use App\EieolHeadWord;
use Illuminate\Http\Request;

class EieolHeadWordKeywordController extends Controller {
	
	public function filtered_list(Request $request)
	{
	    $keywords = EieolHeadWord::where('language_id',$request->get('language'))
            ->get()
            ->map(function($headword) {return explode(',',$headword->keywords);})
            ->flatten()
            ->unique()->values()
            ->filter(function($value) {return $value;})
            ->sort()->values()
        ;
		return $keywords;
	}
	
}
