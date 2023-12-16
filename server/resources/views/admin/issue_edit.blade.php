@extends('admin_layout')

@section('title') Issue #{{$issue->id}} @endsection

@section('head_extra')
    <script src="/assets/tinymce/tinymce-6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        .issue-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: min-content 1fr;
            grid-template-areas: "header header" "textsidebar commentsidebar";
        }

        .header { grid-area: header; }
        .sidebar { max-height:70vh;overflow:scroll;padding-left:25px;padding-right:25px; }
        /** FIXME sidebar max-height is a hack - figure out how to properly get remaining height on page */
        .text-sidebar { grid-area:textsidebar; }
        .comment-sidebar { grid-area:commentsidebar; }

        .comment-card {
            border:solid 1px #333333;
            padding: 5px;
            margin-bottom: 5px;
        }

        .comment-card-header {
            background-color:#CCCCCC;
        }

        .text-panel {
            display: flex;
        }

        .text-card {
            border:solid 1px #999999;
            padding: 5px;
        }

        .highlighter-bin {
            margin-left:5px;
        }

        .highlighter-icon {
            margin-bottom:10px;
            width:30px;
            height:30px;
        }
    </style>
@endsection

@section('content')
    @php
        $issue_pointer_link = null;
        if (str_starts_with($issue->pointer, '/lesson/')) {
            // issue pointer is /lesson/(id)/something.
            // Turn that into the URL /admin2/eieol_lesson/(id)/edit?focus=something#/
            $pointer_parts = explode('/', $issue->pointer, 4);
            $issue_pointer_link = '/admin2/eieol_lesson/'.$pointer_parts[2].'/edit?focus='.$pointer_parts[3].'#/';
        }
    @endphp

    <form method="POST" onsubmit="return checkForm(this);">
        <input type="hidden" name="_method" value="PUT">
        @csrf
    <div>

        <div>
            <div class="issue-container">
                <div class="header">
                    <div style="margin-top:10px;margin-bottom:10px;">
                        <div id="issue_title" style="display:flex;justify-content:space-between;">
                            <h4>#{{$issue->id}}: {{$issue->name}}</h4>
                            <button type="button" id="title_editor_show_button" class="btn btn-sm btn-secondary" @click="openTitleEdit()"
                                onclick="document.getElementById('issue_title').style.display='none';document.getElementById('issue_title_editor').style.display='flex';"
                            >Edit Title</button>
                        </div>
                        <div id="issue_title_editor" style="display:none;justify-content:space-between;">
                            <span>#{{$issue->id}}:
                                 <input id="name" name="name" type="text" value="{{$issue->name}}">
                            </span>
                            <span>
                            </span>
                        </div>
                        <div style="padding-left:10px;padding-right:10px;padding-bottom:10px;">
                            <span @class([
                                'badge badge-success' => $issue->status==='open',
                                'badge badge-danger' => $issue->status==='closed'
                            ])>{{$issue->status}}</span>
                            Created {{$issue->created_at}}
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="sidebar text-sidebar">
                    <h5>Text under discussion</h5>
                    <p><a href="{{$issue_pointer_link}}" target="_blank">{{$issue->pointer_desc}}</a></p>
                    <div class="text-panel">
                        <textarea name="text" id="text">{!! $issue->text !!}</textarea>
                    </div>
                </div>
                <div class="sidebar comment-sidebar">
                    <h5>Comments</h5>
                    @foreach ($issue->comments as $comment)
                    <div class="comment-card">
                        @if ($comment->type==='open')
                        <div>
                            <div class="comment-card-header"><span style="font-weight: bold;">{{$comment->user_logon}}</span>
                                re-opened this issue on {{$comment->created_at}}
                            </div>
                            <p>{!! $comment->text !!}</p>
                        </div>
                        @elseif ($comment->type==='close')
                        <div>
                            <div class="comment-card-header"><span style="font-weight: bold;">{{$comment->user_logon}}</span>
                                closed this issue on {{$comment->created_at}}
                            </div>
                            <p>{!! $comment->text !!}</p>
                        </div>
                        @else
                        <div>
                            <div class="comment-card-header"><span style="font-weight: bold;">{{$comment->user_logon}}</span>
                                commented on {{$comment->created_at}}
                            </div>
                            <p>{!! $comment->text !!}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                    @if ($issue->status==='open')
                    <div class="comment-card">
                        <div class="comment-card-header">Add new comment:</div>
                        <textarea name="comment_text" id="comment_text"></textarea>
                    </div>
                    @endif
                    <div>
                        <button class="btn btn-primary float-right"
                                style="margin:5px;"
                                onclick="this.form.elements.comment_type.value='comment';this.form.submit();"
                        >Comment
                        </button>
                        @if ($issue->status==='open')
                        <button class="btn btn-secondary float-right"
                                style="margin:5px;"
                                onclick="this.form.elements.comment_type.value='close';this.form.elements.status.value='closed';this.form.submit();"
                        >Close
                        </button>
                        @else
                        <button class="btn btn-secondary float-right"
                                style="margin:5px;"
                                onclick="this.form.elements.comment_type.value='open';this.form.elements.status.value='open';this.form.submit();"
                        >Re-Open
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
        <input type="hidden" name="comment_type" value="">
        <input type="hidden" name="status" value="{{$issue->status}}">
    </form>
@endsection

@section('foot_extra')
    <script>
        function validate_title_ok(el) {
            if (el.value.length > 0) {
                el.classList.remove('is-invalid');
                return true;
            } else {
                el.classList.add('is-invalid');
                return false;
            }
        }

        function checkForm(form) {
            if (!validate_title_ok(form.elements.name)) {
                alert("Please enter a title.");
                return false;
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            tinymce.init({
                selector: 'textarea#text',
                branding: false,
                width:"100%",
                height:"500px",
                plugins: "code",
                menubar: '',
                toolbar: "backcolor code",
                promotion: false,
                init_instance_callback: function (editor) {
                    editor.on('keydown', function (e) {
                        e.preventDefault();
                    });
                }
            })

            CKEDITOR.plugins.addExternal( 'onchange', '/js/', 'onchangeplugin.js' );
            CKEDITOR.plugins.addExternal( 'eieol_language', '/ckeditor-plugins/eieol_language/', 'plugin.js');
            CKEDITOR.plugins.addExternal( 'html5audio', '/ckeditor-plugins/html5audio/', 'plugin.js');
            CKEDITOR.replace('comment_text', {
                toolbar:
                    [
                        {name: 'document', items: ['Source', 'EieolLanguage']},
                        {
                            name: 'clipboard',
                            items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']
                        },
                        {name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'SpellChecker']},
                        {
                            name: 'basicstyles',
                            items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']
                        },
                        {
                            name: 'paragraph',
                            items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-',
                                'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']
                        },
                        {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
                        {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar']},
                        {name: 'styles', items: ['Format', 'FontSize']},
                        {name: 'colors', items: ['TextColor', 'BGColor']},
                        {name: 'insert', items: ['Image','Html5audio']},
                        {name: 'tools', items: ['Maximize']}
                    ],
                contentsCss: '/css/lrcstyle.css',
                disableNativeSpellChecker: false,
                allowedContent: true,
                removePlugins: 'image',
                extraPlugins: 'html5audio,filebrowser,onchange,eieol_language,image2',
                filebrowserUploadUrl: '/admin2/files/upload',
                entities: false,
                language_list : {!! json_encode($languages->language_list) !!},
                language_lang : {!! json_encode($languages->language_lang) !!} ,
                specialChars : {!! json_encode($languages->specialChars) !!},
            })
        })
    </script>
@endsection
