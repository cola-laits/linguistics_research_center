<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

class FilesController extends Controller
{

    public function post_file(Request $request)
    {
        if ($request->get('uploader') == 'tinymce') {
            return $this->post_file_tinymce($request);
        } else {
            return $this->post_file_ckeditor($request);
        }
    }

    // uploads from CKEditor
    protected function post_file_ckeditor(Request $request)
    {
        $ret = new \stdClass();

        if (!$request->hasFile('upload')) {
            $ret->uploaded = 0;
            $ret->error = new \stdClass();
            $ret->error->message = 'ERROR: No file chosen.';
            return response()->json($ret);
        }

        $file = $request->file('upload');
        $stored_file = $file->store(date('Y/m/d'));

        $ret->uploaded = 1;
        $ret->fileName = $file->getClientOriginalName();
        $ret->url = Storage::disk()->url($stored_file);
        return response()->json($ret);
    }

    // uploads from TinyMCE
    protected function post_file_tinymce(Request $request)
    {
        $ret = new \stdClass();

        if (!$request->hasFile('file')) {
            $ret->uploaded = 0;
            $ret->error = new \stdClass();
            $ret->error->message = 'ERROR: No file chosen.';
            return response()->json($ret);
        }

        $file = $request->file('file');
        $stored_file = $file->store(date('Y/m/d'));

        $ret->location = Storage::disk()->url($stored_file);
        return response()->json($ret);
    }

}
