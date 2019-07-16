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
                    <p class="text-card" ref="text_card" v-html="issue.text"></p>
                    <div class="highlighter-bin" style="float:left;">
                        <img src="/images/admin/highlighter-1.svg" class="highlighter-icon" @click="highlight(1)"
                             title="highlight selected text blue">
                        <img src="/images/admin/highlighter-2.svg" class="highlighter-icon" @click="highlight(2)"
                             title="highlight selected text pink">
                        <img src="/images/admin/highlighter-3.svg" class="highlighter-icon" @click="highlight(3)"
                             title="highlight selected text yellow">
                        <img src="/images/admin/highlighter-cancel.svg" class="highlighter-icon" @click="highlight(0)"
                             title="un-highlight selected text">
                    </div>
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
    export default {
        props: ['id'],
        data() { return {
            ckeditor_customization: {language_list : [],
                language_lang : '',
                specialChars : []
            },
            issue: {},
            title_editor_is_open: false,
            title_under_edit: '',
            new_comment_text: '',
        }},
        mounted() {

        },
        created() {
            window.axios.get('/admin/api/v1/issue/'+this.id).then((response) => {
                this.issue = response.data.issue;

                window.axios.get('/admin/api/v1/issue/'+this.id+'/languages').then((response) => {
                    let lang = response.data.languages[0];
                    this.ckeditor_customization.language_list = [lang.lang_attribute+':'+lang.language];
                    this.ckeditor_customization.language_lang = [lang.lang_attribute];
                    this.ckeditor_customization.specialChars = lang.custom_keyboard_layout;
                });
            }).catch((error) => {
                console.log(error);
                alert("Error: Unable to fetch data.  Try again.");
            });
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
            highlight(marker_num) {
                let sel = window.getSelection();
                let range = sel.getRangeAt(0);

                // FIXME error message if no range chosen

                // Check to make sure you selected something that's highlightable
                let rangeParent = range.commonAncestorContainer;
                while (rangeParent !== null) {
                    if (rangeParent.className === 'text-card') {
                        break;
                    }
                    rangeParent = rangeParent.parentNode;
                }
                if (rangeParent === null) {
                    // You're not in text-card; abort
                    // FIXME error message alert here
                    return;
                }

                let highlight_color = 'white';
                if (marker_num===1) {
                    highlight_color = '#a6fffe';
                } else if (marker_num===2) {
                    highlight_color = '#fea6ff';
                } else if (marker_num===3) {
                    highlight_color = '#e3ff50';
                }

                let html = '<span style="background-color:'+highlight_color+'">' + range + '</span>';
                let el = document.createElement("div");
                el.innerHTML = html;
                let frag = document.createDocumentFragment(), node, lastNode;
                while ( (node = el.firstChild) ) {
                    lastNode = frag.appendChild(node);
                }
                range.deleteContents();
                range.insertNode(frag);
                sel.removeAllRanges();

                var new_text = this.$refs['text_card'].innerHTML;
                window.axios.put('/admin/api/v1/issue/'+this.id,
                    {'text':new_text}
                ).then((response) => {
                    this.issue.text = response.data.issue.text;
                }).catch((error) => {
                    console.log(error);
                    alert("Error: Unable to save highlight.  Try again.");
                })
            },
            addComment() {
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
                    this.$store.dispatch('updateIssue', response.data.issue);
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
