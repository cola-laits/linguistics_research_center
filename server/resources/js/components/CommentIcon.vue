<template>
    <div>
        <a class="btn btn-sm" :class="button_class"
           :href="'/admin2/issues?pointer='+issue_pointer"
        >
            Issues <span class="badge badge-light">{{issue_count}}</span>
        </a>
        <a class="btn btn-sm btn-outline-secondary"
            :href="'/admin2/issues/create?pointer='+issue_pointer"
        >New Issue</a>
    </div>
</template>

<script>
    export default {
        props: [
            'issue_pointer',
            'issues'
        ],
        computed: {
            issue_count() {
                return this.issues
                    .filter(issue => issue.pointer===this.issue_pointer)
                    .length;
            },
            button_class() {
                if (this.issue_count>0) {
                    return 'btn-warning';
                }
                return 'btn-secondary';
            }
        },
        mounted() {

        }
    }
</script>
