<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{

    public function index() {
        return view('page.page_index', ['pages' => Page::all()]);
    }

    public function create() {
        return view('page.page_form', ['action' => 'Create']);
    }

    public function store(Request $request) {
        $page = new Page;
        $page->slug = $request->get('slug');
        $page->name = $request->get('name');
        $page->content = $request->get('content');
        $page->save();

        $request->session()->flash('message', "Page has been created");
        return redirect('/admin2/page');
    }

    public function edit($id) {
        $page = Page::find($id);
        return view('page.page_form', ['page' => $page,
            'action' => 'Edit', 'slug' => $page->slug]);
    }

    public function update(Request $request, $id) {
        $page = Page::find($id);
        //$page->slug = $request->get('slug');
        $page->name = $request->get('name');
        $page->content = $request->get('content');
        $page->save();

        $request->session()->flash('message', 'Page has been updated');
        return redirect('/admin2/page');

    }

    public function destroy(Request $request, $id) {
        Page::destroy($id);
        $request->session()->flash('message', 'Page has been deleted');
        return redirect('/admin2/page');
    }

}
