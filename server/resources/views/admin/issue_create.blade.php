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
    <div v-cloak id="admin_app">

        <div>
            <div class="issue-container">
                <div class="header">
                    <div style="margin-top:10px;margin-bottom:10px;">
                        <div style="display:flex;justify-content:space-between;">
                            <h5>
                                Issue Title: <input type="text"
                                                    class="form-control"
                                                    :class="{ 'is-invalid': !this.validate_title_ok }"
                                                    style="width: 400px;"
                                                    v-model="issue.name">
                            </h5>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="sidebar text-sidebar">
                    <h5>Text under discussion</h5>
                    <div class="text-panel">
                        <tinymce-editor
                            :init="tinymce_settings"
                            v-model="issue.text"
                        ></tinymce-editor>
                    </div>
                </div>
                <div class="sidebar comment-sidebar">
                    <h5>Comments</h5>
                    <div class="comment-card">
                        <div class="comment-card-header">Add comment:</div>
                        <ck-editor v-model="comment_text"
                                   :custom_config="ckeditor_customization"
                                   v-if="ckeditor_data_ready"
                        ></ck-editor>
                    </div>
                    <div>
                        <button class="btn btn-primary float-right"
                                style="margin:5px;"
                                @click="save()"
                        >Save
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('foot_extra')
    <script>
        var issue = {!! json_encode($issue) !!};
        var languages = {!! json_encode($languages) !!};
        new window.Vue({
            el: '#admin_app',
            data() { return {
                ckeditor_customization: {language_list : [],
                    language_lang : '',
                    specialChars : []
                },
                tinymce_settings: {
                    branding: false,
                    width:"100%",
                    height:"500px",
                    menubar: '',
                    toolbar: "backcolor",
                    init_instance_callback: function (editor) {
                        editor.on('keydown', function (e) {
                            e.preventDefault();
                        });
                    }
                },
                issue: issue,
                languages: languages,
                comment_text: '',
                ckeditor_data_ready: false,
            }},
            mounted() {

            },
            created() {
                this.ckeditor_customization.language_list = this.languages.language_list;
                this.ckeditor_customization.language_lang = this.languages.language_lang;
                this.ckeditor_customization.specialChars = this.languages.specialChars;

                this.ckeditor_data_ready = true;
            },
            computed: {
                validate_title_ok() {
                    if (!this.issue || !this.issue.name) {
                        return false;
                    }
                    return this.issue.name.length > 0;
                }
            },
            methods: {
                save() {
                    if (!this.validate_title_ok) {
                        alert("Please enter a title.");
                        this.$refs['issue_title'].focus();
                        return;
                    }
                    let data = Object.assign({}, this.issue);
                    data.comment_text = this.comment_text;
                    window.axios.post('/admin/api/v1/issue',
                        data
                    ).then((response) => {
                        alert("Issue created.");
                        document.location.href = '/admin2/issues';
                    }).catch((error) => {
                        console.log(error);
                        alert("Error: Unable to save issue.  Try again.");
                    })
                },
            }
        });
    </script>
@endsection
