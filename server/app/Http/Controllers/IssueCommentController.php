<?php

namespace App\Http\Controllers;

use App\Models\IssueComment;
use Auth;
use Illuminate\Http\Request;

class IssueCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $comment = new IssueComment();
        $comment->issue_id = $request->get('issue_id');
        $comment->type = $request->get('type');
        $comment->text = $request->get('text');
        $comment->user_logon = Auth::user()->username;
        $comment->save();
        return response()->json($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\IssueComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(IssueComment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\IssueComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(IssueComment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IssueComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IssueComment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\IssueComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(IssueComment $comment)
    {
        //
    }
}
