<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EieolPartOfSpeechController extends Controller {

    public function filtered_list(Request $request) {
        $data = DB::select("SELECT DISTINCT(part_of_speech) as part_of_speech FROM eieol_element, eieol_gloss"
            . " WHERE eieol_element.gloss_id=eieol_gloss.id"
            . " AND eieol_gloss.language_id = ?"
            . " AND eieol_element.part_of_speech LIKE ?"
            . " ORDER BY part_of_speech LIMIT 25", [
                $request->get('language_id'),
                '%' . $request->get('term') . '%'
        ]);
        return array_map(function($pos) {return $pos->part_of_speech;}, $data);
    }

}
