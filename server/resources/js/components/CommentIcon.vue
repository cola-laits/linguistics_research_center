<template>
    <div :style="style" @click="$emit('click')"><i :class="icon_class"></i></div>
</template>

<script>
    export default {
        props: [
            'author_comment',
            'admin_comment',
            'author_done'
        ],
        computed: {
            style() {
                if (this.author_done) {
                    return 'color:green';
                }
                if (typeof(this.author_comment)!=='undefined') {
                    if (this.author_comment.trim().length > 0 || this.admin_comment.trim().length > 0) {
                        return 'color:red';
                    }
                }
                return '';
            },
            icon_class() {
                if (this.author_done) {
                    return 'fa fa-comment';
                }
                if (typeof(this.author_comment)!=='undefined') {
                    if (this.author_comment.trim().length > 0 || this.admin_comment.trim().length > 0) {
                        return 'fa fa-comment';
                    }
                }
                return 'fa fa-comment-o';
            }
        },
        mounted() {

        }
    }
</script>
