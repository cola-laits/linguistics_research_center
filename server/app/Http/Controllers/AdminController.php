<?php
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpMissingReturnTypeInspection */
/** @noinspection PhpUnused */

namespace App\Http\Controllers;

use App\Models\EieolGloss;
use App\Models\EieolHeadWord;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Normalizer;

class AdminController extends Controller
{
    public function app() {
        return view('admin', []);
    }

    public function analysis_typeahead(Request $request)
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

    public function gloss_typeahead(Request $request)
    {
        //this is a search that returns glosses that start with the url parm "gloss"
        $glosses = EieolGloss::with('elements.head_word')->where(
            'surface_form',
            'LIKE',
            Normalizer::normalize(
                $request->get('gloss'),
                Normalizer::FORM_C
            ) . '%'
        )
            ->where('language_id', '=', $request->get('language') . '%')
            ->take(15)->orderBy('surface_form')
            ->with(['language', 'elements.head_word.language'])
            ->get();

        return [
            'glosses' => $glosses
        ];
    }

    public function part_of_speech_typeahead(Request $request)
    {
        $data = DB::select(
            "SELECT DISTINCT(part_of_speech) as part_of_speech FROM eieol_element, eieol_gloss"
            . " WHERE eieol_element.gloss_id=eieol_gloss.id"
            . " AND eieol_gloss.language_id = ?"
            . " AND eieol_element.part_of_speech LIKE ?"
            . " ORDER BY part_of_speech LIMIT 25",
            [
                $request->get('language_id'),
                '%' . $request->get('term') . '%'
            ]
        );
        return array_map(function ($pos) {
            return $pos->part_of_speech;
        }, $data);
    }

    public function headword_keyword_typeahead(Request $request)
    {
        $keywords = EieolHeadWord::where('language_id', $request->get('language'))
            ->get()
            ->map(function ($headword) {
                return explode(',', $headword->keywords);
            })
            ->flatten()
            ->unique()->values()
            ->filter(function ($value) {
                return $value;
            })
            ->sort()->values();
        return $keywords;
    }

    public function headword_typeahead(Request $request)
    {
        $head_words = EieolHeadWord::where(
            'word',
            'LIKE',
            '%' . Normalizer::normalize(
                $request->get('head_word'),
                Normalizer::FORM_C
            ) . '%'
        )
            ->where('language_id', '=', $request->get('language') . '%')
            ->take(50)->orderBy('word')
            ->with(['language'])
            ->get();

        return [
            'headwords' => $head_words
        ];
    }
}
