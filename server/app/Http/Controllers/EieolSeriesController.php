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

    public function create() {
        return view('eieol_series.eieol_series_form', ['action' => 'Create']);
    }

    public function store(Request $request) {
        $rules = array(
            'published' => 'boolean',
            'order' => 'required|integer',
            'title' => 'required|unique:eieol_series',
            'menu_name' => 'required',
            'menu_order' => 'required|integer',
            'expanded_title' => 'required',
            'slug' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/admin2/eieol_series/create')
                ->withErrors($validator->messages())
                ->withInput();
        }

        $series = new EieolSeries;

        $series->published = $request->get('published');
        $series->order = $request->get('order');
        $series->title = $request->get('title');
        $series->menu_name = $request->get('menu_name');
        $series->menu_order = $request->get('menu_order');
        $series->expanded_title = $request->get('expanded_title');
        $series->meta_tags = $request->get('meta_tags');
        $series->slug = $request->get('slug');
        $series->created_by = Auth::user()->username;
        $series->updated_by = Auth::user()->username;

        $series->save();
        $request->session()->flash('message', $series->title . ' has been created');
        return redirect('/admin2/eieol_series/' . $series->id . '/edit');

    }

    public function edit($id) {
        $series = EieolSeries::find($id);
        $lessons = EieolLesson::where('series_id', '=', $id)->get()->sortBy('order');
        return view('eieol_series.eieol_series_form', ['series' => $series, 'lessons' => $lessons, 'action' => 'Edit']);
    }

    public function update(Request $request, $id) {
        $rules = array(
            'order' => 'required|integer',
            'title' => 'required|unique:eieol_series,title,' . $id,
            'published' => 'boolean',
            'menu_name' => 'required',
            'menu_order' => 'required|integer',
            'expanded_title' => 'required',
            'slug' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/admin2/eieol_series/' . $id . '/edit')
                ->withErrors($validator->messages())
                ->withInput();
        }

        $series = EieolSeries::find($id);

        $series->title = $request->get('title');
        $series->order = $request->get('order');
        $series->published = $request->get('published');
        $series->menu_name = $request->get('menu_name');
        $series->menu_order = $request->get('menu_order');
        $series->expanded_title = $request->get('expanded_title');
        $series->meta_tags = $request->get('meta_tags');
        $series->slug = $request->get('slug');
        $series->updated_by = Auth::user()->username;

        $series->save();
        $request->session()->flash('message', $series->title . ' has been updated');
        return redirect('/admin2/eieol_series/' . $id . '/edit');
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
