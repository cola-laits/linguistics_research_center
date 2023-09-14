<template>
    <div>
        <i v-if="!value" class="fa fa-volume-off" style="cursor:pointer;color:#999;" v-b-modal="id+'-modal'"></i>
        <i v-if="value" class="fa fa-volume-up" style="cursor:pointer;" v-b-modal="id+'-modal'"></i>
        <b-modal :id="id+'-modal'"
                 ok-title="Update"
                 @ok="ok"
                 :ok-disabled="!allow_save"
                 @hide="hide"
        >
            <h5>Text</h5>
            <div :lang="lang">{{text}}</div>
            <hr>

            <h5>Current audio</h5>
            <audio controls :src="url" ref="audio"></audio><br>
            <label :for="id+'-url'">Audio URL</label>
            <input class="form-control" name="audio_url" type="text"
                   autocomplete="off"
                   :id="id+'-url'"
                   @input="allow_save=true"
                   v-model="url">
            <hr>

            <h5>Upload new audio</h5>
            <form enctype="multipart/form-data">
            <input ref="file" type="file" @change="file_change"><br>
            <button type="button"
                    :class="'btn '+(allow_upload?'btn-primary':'btn-secondary')"
                    :disabled="!allow_upload || upload_in_progress"
                    @click="upload"
            >
                {{upload_in_progress ? "Uploading..." : "Upload Selected File"}}
            </button>
            </form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: [
            'id',
            'text',
            'lang',
            'value'
        ],
        data: function() { return {
            url: '',
            allow_upload: false,
            allow_save: false,
            upload_in_progress: false,
        }},
        computed: {

        },
        methods: {
            ok() {
                if (this.url !== this.value) {
                    this.$emit('input', this.url);
                }
            },
            hide() {
                if (this.$refs.audio) {
                    this.$refs.audio.pause();
                }
                this.url = this.value;
                this.allow_upload = false;
                this.allow_save = false;
            },
            file_change() {
                this.allow_upload = true;
            },
            upload() {
                this.upload_in_progress = true;
                let formData = new FormData();
                formData.append('upload',this.$refs.file.files[0]);
                axios.post('/admin2/files/upload', formData, {headers:{'Content-Type':'multipart/form-data'}})
                    .then((response)=>{
                        this.upload_in_progress = false;
                        this.url=response.data.url;
                        alert("Successfully uploaded!  Now click 'update' to save.");
                        this.allow_save = true;
                    });
            }
        },
        mounted() {
            this.url = this.value;
        }
    }
</script>

<style scoped>

</style>
