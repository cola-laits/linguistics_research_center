@extends('admin_layout')

@section('title') New Issue @endsection

@section('head_extra')
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
    </style>

@endsection

@section('content')
    <form method="POST" action="/admin/issue" onsubmit="return checkForm(this);">
        @csrf
        <input type="hidden" name="pointer" value="{{$issue->pointer}}">
        <input type="hidden" name="pointer_desc" value="{{$issue->pointer_desc}}">
    <div>
        <div>
            <div class="issue-container">
                <div class="header">
                    <div style="margin-top:10px;margin-bottom:10px;">
                        <div style="display:flex;justify-content:space-between;">
                            <h5>
                                Issue Title: <input type="text"
                                                    id="issue_title"
                                                    name="name"
                                                    class="form-control"
                                                    onchange="validate_title_ok(this);"
                                                    onkeyup="validate_title_ok(this);"
                                                    style="width: 400px;">
                            </h5>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="sidebar text-sidebar">
                    <h5>Text under discussion</h5>
                    <div class="text-panel">
                        <textarea name="text" id="text">{!! $issue->text !!}</textarea>
                    </div>
                </div>
                <div class="sidebar comment-sidebar">
                    <h5>Comments</h5>
                    <div class="comment-card">
                        <div class="comment-card-header">Add comment:</div>
                        <textarea name="comment_text" id="comment_text"></textarea>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary float-right"
                                style="margin:5px;"
                        >Save
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
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
            validate_title_ok(document.getElementById('issue_title'));

            tinymce.init({
                selector: 'textarea#text',
                branding: false,
                width:"100%",
                height:"500px",
                plugins: "code",
                menubar: '',
                toolbar: "backcolor code",
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
