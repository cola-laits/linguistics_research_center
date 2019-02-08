<?php

namespace App\Http\Controllers;

use App\LexEtyma;
use App\LexLanguage;
use App\LexLanguageFamily;
use App\LexLanguageSubFamily;
use App\LexPartOfSpeech;
use App\LexReflex;
use App\LexReflexEntry;
use App\LexReflexPartOfSpeech;
use App\LexSemanticCategory;
use App\LexSemanticField;
use App\LexSource;
use Illuminate\Http\Request;

class AdminLexiconController extends Controller
{
    public function getIndex() {
        return view('lexicon/lexicon');
    }

    public function getEtymas() {
        return ['data'=>LexEtyma::orderBy('order')->get()];
    }

    public function getReflexes(Request $request) {
        $page = $request->has('page') ? $request->get('page') : 1;
        $data = LexReflex::with('language')->orderBy('language_id')->take(50)->skip(($page-1)*50)->get();
        $data_count = LexReflex::count();
        return [
            'links'=>['pagination'=>[
                'total'=>$data_count,
                'per_page'=>50,
                'current_page'=>$page,
                'last_page'=>ceil($data_count/50)
            ]],
            'data'=>$data
        ];
    }

    public function getReflexEntries(Request $request) {
        $page = $request->has('page') ? $request->get('page') : 1;
        $data = LexReflexEntry::orderBy('reflex_id')->take(50)->skip(($page-1)*50)->get();
        $data_count = LexReflex::count();
        return [
            'links'=>['pagination'=>[
                'total'=>$data_count,
                'per_page'=>50,
                'current_page'=>$page,
                'last_page'=>ceil($data_count/50)
            ]],
            'data'=>$data
        ];
    }

    public function getReflexPOSes(Request $request) {
        $page = $request->has('page') ? $request->get('page') : 1;
        $data = LexReflexPartOfSpeech::orderBy('reflex_id')->take(50)->skip(($page-1)*50)->get();
        $data_count = LexReflexPartOfSpeech::count();
        return [
            'links'=>['pagination'=>[
                'total'=>$data_count,
                'per_page'=>50,
                'current_page'=>$page,
                'last_page'=>ceil($data_count/50)
            ]],
            'data'=>$data
        ];
    }

    public function getSemCats() {
        return ['data'=>LexSemanticCategory::orderBy('number')->get()];
    }

    public function getSemFields() {
        return ['data'=>LexSemanticField::with('semantic_category')->orderBy('number')->get()];
    }

    public function getLangs() {
        $data = LexLanguage::with(['language_sub_family','language_sub_family.language_family'])->orderBy('sub_family_id')->get();
        return ['data'=>$data];
    }

    public function getLangFams() {
        return ['data'=>LexLanguageFamily::orderBy('order')->get()];
    }

    public function getLangSubfams() {
        $data = LexLanguageSubFamily::with('language_family')->orderBy('family_id')->get();
        return ['data'=>$data];
    }

    public function getSources() {
        return ['data'=>LexSource::orderBy('code')->get()];
    }

    public function getPOSes() {
        return ['data'=>LexPartOfSpeech::orderBy('code')->get()];
    }

    private function getClassForName($name) {
        if ($name==='etyma') {
            return LexEtyma::class;
        }
        if ($name==='reflex') {
            return LexReflex::class;
        }
        if ($name==='reflex_entry') {
            return LexReflexEntry::class;
        }
        if ($name==='reflex_pos') {
            return LexReflexPartOfSpeech::class;
        }
        if ($name==='sem_cat') {
            return LexSemanticCategory::class;
        }
        if ($name==='sem_field') {
            return LexSemanticField::class;
        }
        if ($name==='lang_fam') {
            return LexLanguageFamily::class;
        }
        if ($name==='lang_subfam') {
            return LexLanguageSubFamily::class;
        }
        if ($name==='lang') {
            return LexLanguage::class;
        }
        if ($name==='source') {
            return LexSource::class;
        }
        if ($name==='pos') {
            return LexPartOfSpeech::class;
        }
        throw new \Exception("Unknown class: ".$name);
    }

    public function getItem(Request $request) {
        $class = $this->getClassForName($request->type);

        if ($request->id==='new') {
            return ['item'=>new \stdClass()];
        }
        return ['item'=>$class::findOrFail($request->id)];
    }

    public function postEditItem(Request $request) {
        $class = $this->getClassForName($request->type);

        if ($request->id==='new') {
            $obj = new $class();
        } else {
            $obj = $class::findOrFail($request->id);
        }

        $obj->fill($request->item);
        $obj->save();
    }

    public function postDeleteItem(Request $request) {
        $class = $this->getClassForName($request->type);
        $class::findOrFail($request->id)->delete($request->id);
    }
}
