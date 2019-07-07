<?php

namespace App\Http\Controllers;

use App\EieolLanguage;
use App\EieolLesson;
use App\Issue;
use App\IssueComment;
use Illuminate\Http\Request;
use \Auth;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $issues = Issue::with('comments')->orderBy('id');
        if (Auth::user()->isAdmin()) {
            // show 'em all
        } else {
            $issues = $issues->whereHas('comments', function ($query) {
                $query->where('user_logon', Auth::user()->username);
            });
        }

        return response()->json(['issues'=>$issues->get()]);
    }

    public function getLanguages($id) {
        $issue = Issue::findOrFail($id);
        $re = '/^\/lesson\/(\d*)\/.*/m';
        preg_match_all($re, $issue->pointer, $matches, PREG_SET_ORDER);
        $lesson = EieolLesson::findOrFail($matches[0][1]);
        $language_id = $lesson->language_id;
        $language = EieolLanguage::findOrFail($language_id);
        // convert CSV of quoted entries to an array of unquoted ones
        $language->custom_keyboard_layout = array_map(
            function($item) {
                return str_replace('\'','',$item);
            },
            explode(',', $language->custom_keyboard_layout)
        );
        $languages = [$language];
        return response()->json(['languages'=>$languages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // add a 'created by' comment too
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function show(Issue $issue)
    {
        $issue->comments = $issue->comments; // eager-load comments
        return response()->json(['issue'=>$issue]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function edit(Issue $issue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Issue $issue)
    {
        $new_comment_type = "";
        if ($request->has('status') && $issue->status !== $request->get('status')) {
            if ($request->get('status')==='open') { $new_comment_type='open';}
            if ($request->get('status')==='closed') { $new_comment_type='close';}
        }
        $issue->update($request->all());
        if ($new_comment_type) {
            $comment = new IssueComment();
            $comment->issue_id = $issue->id;
            $comment->type = $new_comment_type;
            $comment->text = "";
            $comment->user_logon = Auth::user()->username;
            $comment->save();
        }
        return response()->json(['issue'=>$issue]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Issue $issue)
    {
        //
    }
}
