<?php


namespace App\Http\Controllers;


use App\Page;

class PublicPageController
{
    public function index() {
        return view('index', [
            'content' => Page::whereSlug('index')->first()->content
        ]);
    }

    public function guide_ea() {
        return view('guide_ea', [
            'content' => Page::whereSlug('guides/eieol_author')->first()->content
        ]);
    }

    public function guide_eu() {
        return view('guide_eu', [
            'content' => Page::whereSlug('guides/eieol_user')->first()->content
        ]);
    }

    public function guide_lu() {
        return view('guide_lu', [
            'content' => Page::whereSlug('guides/lex_user')->first()->content
        ]);
    }

}
