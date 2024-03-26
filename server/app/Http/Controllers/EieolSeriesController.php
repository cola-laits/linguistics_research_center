<?php

namespace App\Http\Controllers;

use App\Models\EieolLesson;
use App\Models\EieolSeries;
use App\Models\EieolSeriesLanguage;
use App\Models\IsoLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EieolSeriesController extends Controller
{

    public function index() {
        if (Auth::user()->isAdmin()) {
            $serieses = EieolSeries::all()->sortBy('order');
        } else {
            $serieses = Auth::user()->editableSeries->sortBy('order');
        }
        return view('admin.eieol_series_index', ['serieses' => $serieses]);
    }

    public function edit($id) {
        $series = EieolSeries::findOrFail($id);
        $lessons = $series->lessons;
        $attached_languages = $series->languages->map(fn ($language) => [
            'id' => $language->id,
            'text' => $language->display,
            'value' => $language->lang,
        ]);
        return view('admin.eieol_series_form', [
            'series' => $series,
            'lessons' => $lessons,
            'languages' => $this->all_languages(),
            'attached_languages' => $attached_languages,
            'action' => 'Edit'
        ]);
    }

    protected function all_languages() {
        $languages = IsoLanguage::whereIn('Language_Type', array('E', 'A', 'H', 'G'))
            ->orWhere('Part1', '!=', '')
            ->orWhere('Part2B', '!=', '')
            ->orWhere('Part2T', '!=', '')
            ->orderBy('Ref_Name')
            ->get()
            ->filter(function ($language) {
                return $language->Language_Type !== 'S' &&
                    $language->Language_Type !== 'C' &&
                    !str_starts_with($language->Ref_Name, "/") &&
                    !str_starts_with($language->Ref_Name, "#");
            })
            ->map(fn($language) => [
                'text' => $language->Ref_Name,
                'value' => strlen($language->Part1) === 2 ? $language->Part1 : $language->iso_id
            ]);

        return $languages;
    }

    public function attach_language(Request $request) {
        $language = new EieolSeriesLanguage;
        $language->series_id = $request->get('id');
        $language->lang = $request->get('lang');
        $language->display = IsoLanguage::where('iso_id', '=', $language->lang)->firstOrFail()->Ref_Name;
        $language->save();

        return redirect('/admin2/eieol_series/' . $language->series_id . '/edit');
    }

    public function detach_language($series_id, $language_id) {
        $language = EieolSeriesLanguage::where('series_id', '=', $series_id)->where('id', '=', $language_id)->firstOrFail();
        $language->delete();

        return redirect('/admin2/eieol_series/' . $series_id . '/edit');
    }

}
