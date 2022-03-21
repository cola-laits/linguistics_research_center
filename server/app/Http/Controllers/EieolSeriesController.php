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
        return view('eieol_series.eieol_series_index', ['serieses' => $serieses]);
    }

    public function edit($id) {
        $series = EieolSeries::find($id);
        $lessons = EieolLesson::where('series_id', '=', $id)->get()->sortBy('order');
        return view('eieol_series.eieol_series_form', ['series' => $series, 'lessons' => $lessons, 'action' => 'Edit']);
    }

    public function all_languages() {
        $return_languages = array();
        $languages = IsoLanguage::whereIn('Language_Type', array('E', 'A', 'H', 'G'))
            ->orWhere('Part1', '!=', '')
            ->orWhere('Part2B', '!=', '')
            ->orWhere('Part2T', '!=', '')
            ->get()
            ->sortBy('Ref_Name');
        foreach ($languages as $language) {
            $temp_dict = array();
            $temp_dict['text'] = $language->Ref_Name;
            $temp_dict['value'] = strlen($language->Part1) === 2 ? $language->Part1 : $language->iso_id;
            if (
                $language->Language_Type !== 'S' &&
                $language->Language_Type !== 'C' &&
                strpos($temp_dict['text'], "/") !== 0 &&
                strpos($temp_dict['text'], "#") !== 0
            ) {
                $return_languages[] = $temp_dict;
            }
        }

        return $return_languages;
    }

    public function attached_languages($series_id) {
        $return_languages = array();
        $series = EieolSeries::with('languages')->find($series_id);
        $languages = $series->languages;
        foreach ($languages as $language) {
            $temp_dict = array();
            $temp_dict['text'] = $language->display;
            $temp_dict['value'] = $language->lang;
            $return_languages[] = $temp_dict;
        }
        return $return_languages;
    }

    public function attach_language(Request $request) {

        $language = new EieolSeriesLanguage;
        $language->series_id = $request->get('id');
        $language->lang = $request->get('lang');
        $language->display = $request->get('display');
        $language->save();

        return ['text' => $language->display, 'value' => $language->lang];
    }

    public function detach_language($series_id, $language_id) {

        $language = EieolSeriesLanguage::where('series_id', '=', $series_id)->where('lang', '=', $language_id)->firstOrFail();
        $arr_lang = array('text' => $language->display, 'value' => $language->lang);

        $language->delete();

        return $arr_lang;
    }

}
