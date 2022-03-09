<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class PublicBookController extends Controller
{
    public function bookHome($bookSlug) {
        $book = Book::where('slug', $bookSlug)->firstOrFail();
        $sections = $book->sections;
        if (count($sections) === 0) {
            abort(404);
        }
        return redirect('/books/' . $bookSlug . '/' . $sections[0]->slug);
    }

    public function bookSection($bookSlug, $sectionSlug) {
        $book = Book::where('slug', $bookSlug)->firstOrFail();
        $section = $book->sections->firstWhere('slug', $sectionSlug);
        if (!$section) {
            abort(404);
        }
        $prev_section = $book->sections->reverse()->firstWhere('order','<',$section->order);
        $next_section = $book->sections->firstWhere('order','>',$section->order);
        return view('book_section', [
            'book'=>$book,
            'section'=>$section,
            'prev_section'=>$prev_section,
            'next_section'=>$next_section,
        ]);
    }
}
