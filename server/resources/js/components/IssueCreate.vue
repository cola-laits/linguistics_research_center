<template>
    <div>
        <div class="issue-container">
            <div class="header">
                <div style="margin-top:10px;margin-bottom:10px;">
                    <div style="display:flex;justify-content:space-between;">
                        <h5>
                            Issue Title: <b-form-input type="text" ref="issue_title" v-model="issue.name" style="width:400px;"
                                                       :state="this.validate_title_ok"/>
                        </h5>
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
    import tinymce from 'tinymce';
    import 'tinymce/themes/silver';
    import Editor from '@tinymce/tinymce-vue';

    export default {
        components: {
            'tinymce-editor': Editor
        },
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
                    this.$router.push('/issues');
                }).catch((error) => {
                    console.log(error);
                    alert("Error: Unable to save issue.  Try again.");
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
</style>
