<?php

class EieolGlossController extends BaseController {	
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//this does NOT list all glosses.  It's is a search that returns glosses that start with the url parm "gloss"
		$text = '';
		$glosses = EieolGloss::with('head_word')->where('surface_form', 'LIKE', Input::get('gloss') . '%')->take(25)->get()->sortBy('surface_form');
		foreach ($glosses as $gloss) {
			$text .= '<a id="' . $gloss->id . '">' .
					 $gloss->surface_form . ' -- ' . 
					 $gloss->part_of_speech . '; ' . 
					 $gloss->analysis . ' ' .
					 htmlentities($gloss->head_word->word) . ' ' .
					 $gloss->head_word->definition .
					 '<strong> -- ' . $gloss->contextual_gloss . '</strong>' .
					 '</a>' .
					 '<br/>';				
		}
		if (count($glosses) == 0) {
			return 'No matching glosses found';
		} else {
			return $text;
		}
	}
	
}