<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EieolAnalysisController extends Controller {
	
	public function filtered_list(Request $request)
	{
        $data = DB::select("SELECT DISTINCT(analysis) as analysis FROM eieol_element, eieol_gloss"
            . " WHERE eieol_element.gloss_id=eieol_gloss.id"
            . " AND eieol_gloss.language_id = ?"
            . " AND eieol_element.analysis LIKE ?"
            . " ORDER BY analysis LIMIT 25", [
            $request->get('language_id'),
            '%' . $request->get('term') . '%'
        ]);
        return array_map(function($anl) {return $anl->analysis;}, $data);
	}
	
}
