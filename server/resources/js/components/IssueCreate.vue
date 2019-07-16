<template>
    <div>
        <div class="issue-container">
            <div class="header">
                <div style="margin-top:10px;margin-bottom:10px;">
                    <div style="display:flex;justify-content:space-between;">
                        <h5>Issue Title: <input type="text" ref="issue_title" v-model="issue.name" style="width:400px;"></h5>
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
                <div class="comment-card">
                    <div class="comment-card-header">Add comment:</div>
                    {{comment_text}}
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
</template>

<script>
    export default {
        data() { return {
            ckeditor_customization: {language_list : [],
                language_lang : '',
                specialChars : []
            },
            issue: {},
            comment_text: '',
            ckeditor_data_ready: false,
        }},
        mounted() {

        },
        created() {
            let pointer = this.$route.query.pointer;
            window.axios.get('/admin/api/v1/issue/create?pointer='+pointer).then((response) => {
                this.issue = response.data.issue;

                let lang = response.data.languages[0];
                this.ckeditor_customization.language_list = [lang.lang_attribute+':'+lang.language];
                this.ckeditor_customization.language_lang = [lang.lang_attribute];
                this.ckeditor_customization.specialChars = lang.custom_keyboard_layout;

                this.ckeditor_data_ready = true;
            }).catch((error) => {
                console.log(error);
                alert("Error: Unable to fetch data.  Try again.");
            });
        },
        computed: {

        },
        methods: {
            save() {
                let data = Object.assign({}, this.issue);
                data.comment_text = this.comment_text;
                window.axios.post('/admin/api/v1/issue',
                    data
                ).then((response) => {
                    alert("Issue created.");
                    this.$router.push('/issues');
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

                this.issue.text = this.$refs['text_card'].innerHTML;
            },
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
