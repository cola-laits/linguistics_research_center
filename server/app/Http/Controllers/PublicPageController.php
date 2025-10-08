<?php


namespace App\Http\Controllers;


use App\Models\Page;

class PublicPageController extends Controller
{
    public function index()
    {
        return view('index', [
            'content' => Page::whereSlug('index')->firstOrFail()->content
        ]);
    }

    public function guide_ea()
    {
        return view('guide_ea', [
            'content' => Page::whereSlug('guides/eieol_author')->firstOrFail()->content
        ]);
    }

    public function guide_eu()
    {
        return view('guide_eu', [
            'content' => Page::whereSlug('guides/eieol_user')->firstOrFail()->content
        ]);
    }

    public function guide_lu()
    {
        return view('guide_lu', [
            'content' => Page::whereSlug('guides/lex_user')->firstOrFail()->content
        ]);
    }

    public function lex()
    {
        return view('lex', [
            'content' => Page::whereSlug('lex')->firstOrFail()->content
        ]);
    }

    public function books()
    {
        return view('page', [
            'page' => Page::whereSlug('books')->firstOrFail()
        ]);
    }
}
