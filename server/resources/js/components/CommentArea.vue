<template>
    <transition name="comment-slide">
        <div class="row comment_rows" v-if="show_comments_area">
            <div class='form-group col-sm-9 offset-1'>
                <label :for="author_comments_prop">Author Comments</label>
                <textarea class="form-control comment_textarea"
                          :name="author_comments_prop"
                          cols="100"
                          rows="2"
                          :id="author_comments_prop"
                          v-model="value[author_comments_prop]"
                          @input="input()"
                ></textarea>
            </div>

            <div class='form-group col-sm-1'>
                <div class="form-check">
                    <input class="form-check-input author_done"
                           :name="author_done_prop"
                           type="checkbox"
                           value="1"
                           :id="author_done_prop"
                           v-model="value[author_done_prop]"
                           @input="input()"
                    >
                    <label :for="author_done_prop" class="form-check-label">Done</label>
                </div>
            </div>

            <div class='form-group col-sm-9 offset-1'>
                <label :for="admin_comments_prop">Admin Comments</label>
                <div v-if="is_user_admin">
                        <textarea
                            class="form-control comment_textarea"
                            :name="admin_comments_prop"
                            cols="100"
                            rows="2"
                            :id="admin_comments_prop"
                            v-model="value[admin_comments_prop]"
                            @input="input()"
                        ></textarea>
                </div>
                <div v-else>
                    <input type="hidden" name="admin_comments" v-model="value[admin_comments_prop]">
                    <div class="card"><div class="card-body" style="white-space: pre-wrap">{{value[admin_comments_prop]}}</div></div>
                </div>
            </div>

            <div class='form-group col-sm-1'>
                <button class="btn btn-sm btn-warning" type="button" @click="clear_comments()">Clear</button>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        props: [
            'is_user_admin',
            'show_comments_area',
            'value',
            'author_comments_prop_name',
            'admin_comments_prop_name',
            'author_done_prop_name'
        ],
        computed: {
            author_comments_prop() {
                return this.author_comments_prop_name || "author_comments";
            },
            author_done_prop() {
                return this.author_done_prop_name || "author_done";
            },
            admin_comments_prop() {
                return this.admin_comments_prop_name || "admin_comments";
            }
        },
        methods: {
            input() {
                this.$emit('input', this.value);
            },
            clear_comments() {
                this.value[this.author_comments_prop] = '';
                this.value[this.author_done_prop] = false;
                this.value[this.admin_comments_prop] = '';
                this.$emit('input', this.value);
            }
        },
        mounted() {

        }
    }
</script>

<style scoped>
    .comment-slide-enter,
    .comment-slide-leave-to { opacity: 0 }

    .comment-slide-leave,
    .comment-slide-enter-to { opacity: 1 }

    .comment-slide-enter-active,
    .comment-slide-leave-active { transition: opacity 300ms }
</style>
