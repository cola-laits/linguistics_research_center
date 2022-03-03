<?php

namespace App\Http\Controllers;

use App\Models\EieolLanguage;
use App\Models\EieolLesson;
use App\Models\EieolSeries;
use App\Models\Issue;
use App\Models\IssueComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Auth;

class IssueController extends Controller
{
    public function index() : JsonResponse
    {
        $issues = Issue::with('comments')->orderBy('id');
        if (!Auth::user()?->isAdmin()) {
            $serieses = Auth::user()->editableSeries->sortBy('order');
            foreach ($serieses as $series) {
                foreach ($series->lessons as $lesson) {
                    $issues->orWhere('pointer', 'like', '/lesson/' . $lesson->id . '/%');
                }
            }
            $issues = $issues->distinct();
        }

        return response()->json(['issues'=>$issues->get()]);
    }

    public function getLanguages($id) : JsonResponse {
        $issue = Issue::findOrFail($id);
        $pointer = $issue->pointer;
        $languages = self::getLanguagesForPointer($pointer);
        return response()->json(['languages'=>$languages]);
    }

    protected static function getLanguagesForPointer($pointer) {
        $re = '/^\/lesson\/(\d*)\/.*/m';
        preg_match_all($re, $pointer, $matches, PREG_SET_ORDER);
        $lesson = EieolLesson::findOrFail($matches[0][1]);
        $language = EieolLanguage::findOrFail($lesson->language_id);
        // convert CSV of quoted entries to an array of unquoted ones
        $language->custom_keyboard_layout = array_map(
            function($item) {
                return str_replace('\'','',$item);
            },
            explode(',', $language->custom_keyboard_layout)
        );
        $result = new \stdClass;
        $result->language_list = [$language->lang_attribute.':'.$language->language];
        $result->language_lang = [$language->lang_attribute];
        $result->specialChars = $language->custom_keyboard_layout;

        foreach ($lesson->series->languages as $add_lang) {
            $result->language_list []= $add_lang->lang.':'.$add_lang->display;
            $result->language_lang []= $add_lang->lang;
        }
        return $result;
    }

    public function create(Request $request) : JsonResponse
    {
        $pointer = $request->get('pointer');
        $issue = new Issue();
        $issue->name = '';
        $issue->text = Issue::getTextFromPointer($pointer);
        $issue->pointer = $pointer;
        $issue->pointer_desc = Issue::getPointerDescFromPointer($pointer);
        $issue->status = 'open';
        $languages = self::getLanguagesForPointer($pointer);
        return response()->json(['issue'=>$issue, 'languages'=>$languages]);
    }

    public function store(Request $request) : JsonResponse
    {
        $issue = new Issue();
        $issue->name = $request->get('name');
        $issue->text = $request->get('text');
        $issue->pointer = $request->get('pointer');
        $issue->pointer_desc = $request->get('pointer_desc');
        $issue->status = 'open';
        $issue->save();

        $comment_text = $request->get('comment_text');
        if (!$comment_text) {
            $comment_text = 'Issue created';
        }
        $comment = new IssueComment();
        $comment->issue_id = $issue->id;
        $comment->type = 'comment';
        $comment->text = $comment_text;
        $comment->user_logon = Auth::user()->username;
        $comment->save();

        return response()->json(['issue'=>$issue]);
    }

    public function show(Issue $issue) : JsonResponse
    {
        $issue->load('comments');
        return response()->json(['issue'=>$issue]);
    }

    public function update(Request $request, Issue $issue) : JsonResponse
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
}
