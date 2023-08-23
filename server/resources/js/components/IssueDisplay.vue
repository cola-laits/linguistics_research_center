<template>
    <div>
        <div class="issue-container">
            <div class="header">
                <div style="margin-top:10px;margin-bottom:10px;">
                    <div style="display:flex;justify-content:space-between;" v-show="!title_editor_is_open">
                        <h4>#{{issue.id}}: {{issue.name}}</h4>
                        <button class="btn btn-sm btn-secondary" @click="openTitleEdit()">Edit</button>
                    </div>
                    <div style="display:flex;justify-content:space-between;" v-show="title_editor_is_open">
                        <span>#{{issue.id}}: <input type="text" v-model="title_under_edit" style="width:400px;"></span>
                        <span>
                        <button class="btn btn-sm btn-secondary" @click="cancelTitleEdit()">Cancel</button>
                        <button class="btn btn-sm btn-primary" @click="saveTitleEdit()">Save</button>
                        </span>
                    </div>
                    <div style="padding-left:10px;padding-right:10px;padding-bottom:10px;">
                        <span :class="badge_css_class(issue)">{{issue.status}}</span>
                        Created {{getIssueCreatedAtDate(issue)}}
                    </div>
                    <hr>
                </div>
            </div>
            <div class="sidebar text-sidebar">
                <h5>Text under discussion</h5>
                <p><a :href="getIssueLink(issue)" target="_blank">{{issue.pointer_desc}}</a></p>
                <div class="text-panel">
                    <tinymce-editor
                        :init="tinymce_settings"
                        v-model="issue.text"
                    ></tinymce-editor>
                </div>
            </div>
            <div class="sidebar comment-sidebar">
                <h5>Comments</h5>
                <div class="comment-card" v-for="comment in issue.comments">
                    <div v-if="comment.type==='open'">
                        <div class="comment-card-header"><span style="font-weight: bold;">{{comment.user_logon}}</span>
                            re-opened this issue on {{formatTimestampForDisplay(comment.created_at)}}
                        </div>
                        <p v-html="comment.text"></p>
                    </div>
                    <div v-else-if="comment.type==='close'">
                        <div class="comment-card-header"><span style="font-weight: bold;">{{comment.user_logon}}</span>
                            closed this issue on {{formatTimestampForDisplay(comment.created_at)}}
                        </div>
                        <p v-html="comment.text"></p>
                    </div>
                    <div v-else>
                        <div class="comment-card-header"><span style="font-weight: bold;">{{comment.user_logon}}</span>
                            commented on {{formatTimestampForDisplay(comment.created_at)}}
                        </div>
                        <p v-html="comment.text"></p>
                    </div>
                </div>
                <div class="comment-card" v-show="is_issue_open">
                    <div class="comment-card-header">Add new comment:</div>
                    <ck-editor v-model="new_comment_text"
                               :custom_config="ckeditor_customization"
                               v-if="ckeditor_data_ready"
                    ></ck-editor>
                </div>
                <div>
                    <button class="btn btn-primary float-right"
                            style="margin:5px;"
                            @click="addComment()"
                    >Comment
                    </button>
                    <button class="btn btn-secondary float-right"
                            style="margin:5px;"
                            v-show="is_issue_open"
                            @click="setIssueStatus('closed')"
                    >Close
                    </button>
                    <button class="btn btn-secondary float-right"
                            style="margin:5px;"
                            v-show="!is_issue_open"
                            @click="setIssueStatus('open')"
                    >Re-Open
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import tinymce from 'tinymce';
    import 'tinymce/themes/silver';
    import 'tinymce/plugins/code';
    import Editor from '@tinymce/tinymce-vue';

    import CKEditor from './CkEditor'

    export default {
        props: [
            'id',
            'issue_json',
            'languages_json'
        ],
        components: {
            'ck-editor': CKEditor,
            'tinymce-editor': Editor,
        },
        data() { return {
            ckeditor_customization: {},
            ckeditor_data_ready: false,
            tinymce_settings: {
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
            },
            issue: JSON.parse(this.issue_json),
            languages: JSON.parse(this.languages_json),
            title_editor_is_open: false,
            title_under_edit: '',
            new_comment_text: '',
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
            is_issue_open() {
                return this.issue.status === 'open';
            }
        },
        methods: {
            openTitleEdit() {
                this.title_under_edit = this.issue.name;
                this.title_editor_is_open = true;
            },
            cancelTitleEdit() {
                this.title_editor_is_open = false;
            },
            saveTitleEdit() {
                window.axios.put('/admin/api/v1/issue/'+this.id,
                    {'name':this.title_under_edit}
                ).then((response) => {
                    this.issue.name = response.data.issue.name;
                    this.title_editor_is_open = false;
                }).catch((error) => {
                    console.log(error);
                    alert("Error: Unable to save title.  Try again.");
                })
            },
            getIssueLink(issue) {
                if (issue && issue.pointer && issue.pointer.indexOf('/lesson/')===0) {
                    // issue pointer is /lesson/(id)/something.
                    // Turn that into the URL /admin2/eieol_lesson/(id)/edit?focus=something#/
                    let temp = issue.pointer.substring(8);
                    let lesson_id = temp.substring(0,temp.indexOf('/'));
                    let part = temp.substring(temp.indexOf('/')+1);
                    return '/admin2/eieol_lesson/'+lesson_id+'/edit?focus='+part+'#/';
                }
                return false;
            },
            getIssueCreatedAtDate(issue) {
                if (!issue.created_at) {
                    return '';
                }
                return issue.created_at.split(' ')[0];
            },
            formatTimestampForDisplay(ts) {
                return ts.split(' ')[0];
            },
            badge_css_class(issue) {
                if (issue.status==='open') {
                    return 'badge badge-success';
                } else {
                    return 'badge badge-danger';
                }
            },
            addComment() {
                window.axios.put('/admin/api/v1/issue/'+this.id,
                    {'text':this.issue.text}
                ).then((response) => {
                    this.issue.text = response.data.issue.text;
                }).catch((error) => {
                    console.log(error);
                    alert("Error: Unable to save highlight.  Try again.");
                });

                if (this.new_comment_text.trim()==='' || this.new_comment_text.trim()==='<p></p>') {
                    return;
                }

                window.axios.post('/admin/api/v1/issue_comment', {
                    'issue_id':this.issue.id,
                    'type':'comment',
                    'text':this.new_comment_text
                }).then((response) => {
                    this.issue.comments.push(response.data);
                    this.new_comment_text = '';
                }).catch((error) => {
                    console.log(error);
                    alert("Error: Unable to add comments.  Try again.");
                })
            },
            setIssueStatus(status) {
                this.addComment();
                window.axios.put('/admin/api/v1/issue/'+this.id,
                    {'status':status}
                ).then((response) => {
                    this.issue.status = response.data.issue.status;
                }).catch((error) => {
                    console.log(error);
                    alert("Error: Unable to change issue status.  Try again.");
                })
            }
        }
    }
</script>

<style scoped>
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
