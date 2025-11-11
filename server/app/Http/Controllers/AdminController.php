<?php

namespace App\Http\Controllers;

use App\Models\EieolElement;
use App\Models\EieolGloss;
use App\Models\EieolHeadWord;
use Illuminate\Http\Request;
use Normalizer;

class AdminController extends Controller
{
    public function analysis_typeahead(Request $request)
    {
        return EieolElement::query()
            ->join('eieol_gloss', 'eieol_element.gloss_id', '=', 'eieol_gloss.id')
            ->where('eieol_gloss.language_id', $request->get('language_id'))
            ->where('eieol_element.analysis', 'LIKE', '%' . $request->get('term') . '%')
            ->distinct()
            ->orderBy('eieol_element.analysis')
            ->pluck('eieol_element.analysis');
    }

    public function gloss_typeahead(Request $request)
    {
        // Eloquent query that returns glosses starting with the supplied "gloss" parameter.
        $glosses = EieolGloss::with(['elements.head_word.language', 'language'])
            ->where('surface_form', 'LIKE', Normalizer::normalize(
                    $request->get('gloss'),
                    Normalizer::FORM_C
                ) . '%')
            ->where('language_id', $request->get('language'))
            ->orderBy('surface_form')
            ->take(15)
            ->get();

        return [
            'glosses' => $glosses,
        ];
    }

    public function part_of_speech_typeahead(Request $request)
    {
        return EieolElement::query()
            ->join('eieol_gloss', 'eieol_element.gloss_id', '=', 'eieol_gloss.id')
            ->where('eieol_gloss.language_id', $request->get('language_id'))
            ->where('eieol_element.part_of_speech', 'LIKE', '%' . $request->get('term') . '%')
            ->orderBy('eieol_element.part_of_speech')
            ->distinct()
            ->limit(25)
            ->pluck('eieol_element.part_of_speech');

    }

    public function headword_keyword_typeahead(Request $request)
    {
        return EieolHeadWord::where('language_id', $request->get('language'))
            ->get()
            ->map(fn($headword) => explode(',', $headword->keywords))
            ->flatten()
            ->unique()->values()
            ->filter(fn($value) => $value) // non-null values only
            ->sort()->values();
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
            ->where('language_id', $request->get('language'))
            ->take(50)->orderBy('word')
            ->with(['language'])
            ->get();

        return [
            'headwords' => $head_words
        ];
    }
}
