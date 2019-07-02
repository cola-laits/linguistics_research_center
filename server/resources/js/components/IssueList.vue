<template>
    <div>
        <div class="d-flex" style="padding-top:10px;padding-bottom:10px;">
            <h2>Issues</h2>
            <div class="btn-group" style="padding-left:10px;">
                <button type="button"
                        class="btn btn-sm"
                        :class="issue_status_button_css_class('open')"
                        @click.prevent="toggle_issue_status($event, 'open')"
                >Open</button>
                <button type="button"
                        class="btn btn-sm"
                        :class="issue_status_button_css_class('closed')"
                        @click="toggle_issue_status($event, 'closed')"
                >Closed</button>
            </div>
        </div>
        <div v-if="this.sorted_issues.length===0">
            No issues matching current search criteria.
        </div>
        <div class="issue_list">
            <div class="issue" v-for="issue in sorted_issues">
                <div style="cursor:pointer;" class="card" :class="card_css_class(issue.id)"
                     @click="click_issue(issue)"
                     @mouseover="highlight_issue(issue)"
                     @mouseout="unhighlight_issue(issue)"
                >
                    <div class="card-header">
                        <h5>
                            <span :class="badge_css_class(issue)">{{issue.status}}</span>
                            #{{issue.id}}:
                            {{issue.name}}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div style="font-weight:bold;">In: {{issue.pointer_desc}}</div>
                        <div>{{issue.comments.length}} comments, last on {{get_last_issue_comment(issue).created_at.split(' ')[0]}} by {{get_last_issue_comment(issue).user_logon}}</div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [],
        data() { return {
            highlighted_issue_id:-1,
            issue_statuses: ['open'],
        }},
        mounted() {

        },
        created() {
            this.$store.dispatch('loadIssues');
        },
        computed: {
            issues() {
                return this.$store.state.issues;
            },
            filtered_issues() {
                return this.issues.filter(issue => {
                    return this.issue_statuses.includes(issue.status);
                });
            },
            sorted_issues() {
                return this.filtered_issues.sort((a,b) => {
                    return ('' + a.created_at).localeCompare('' + b.created_at);
                });
            }
        },
        methods: {
            issue_status_button_highlighted(status) {
                return this.issue_statuses.includes(status);
            },
            issue_status_button_css_class(status) {
                return this.issue_status_button_highlighted(status) ? 'btn-secondary' : 'btn-outline-secondary';
            },
            toggle_issue_status(evt, status) {
                if (this.issue_status_button_highlighted(status)) {
                    this.issue_statuses.splice(this.issue_statuses.indexOf(status), 1);
                } else {
                    this.issue_statuses.push(status);
                }
                evt.target.blur();
            },
            badge_css_class(issue) {
                if (issue.status==='open') {
                    return 'badge badge-success';
                } else {
                    return 'badge badge-danger';
                }
            },
            card_css_class(issue_id) {
                if (issue_id === this.highlighted_issue_id) {
                    return 'border-primary';
                }
                return '';
            },
            get_last_issue_comment(issue) {
                return issue.comments[issue.comments.length-1];
            },
            click_issue(issue) {
                this.$router.push('/issue/'+issue.id);
            },
            highlight_issue(issue) {
                this.highlighted_issue_id = issue.id;
            },
            unhighlight_issue(issue) {
                if (this.highlighted_issue_id === issue.id) {
                    this.highlighted_issue_id = -1;
                }
            },
        }
    }
</script>
