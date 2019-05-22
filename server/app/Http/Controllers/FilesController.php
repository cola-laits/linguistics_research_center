<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

class FilesController extends Controller
{

    public function post_file(Request $request) {
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
        //$ret->url = env('MINIO_ENDPOINT') . '/' . env('MINIO_BUCKET') . '/' . $stored_file;
        $ret->url = Storage::disk()->url($stored_file);
        return response()->json($ret);
    }

}
