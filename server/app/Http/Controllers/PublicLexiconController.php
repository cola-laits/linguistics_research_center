<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicLexiconController extends Controller
{
    public function index(Request $request, $lexicon_slug) {
        //display lexicon, filters for language, etc
    }

    public function language_index() {
        // list of words for a particular language
    }

    public function etymological_index() {
        // list of roots in the reconstructed language for this lexicon
    }

    public function semantic_index() {
        // like IELEX
    }

}
