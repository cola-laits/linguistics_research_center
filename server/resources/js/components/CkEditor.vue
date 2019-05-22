<template>
    <textarea class="form-control" :name="html_name" cols="100" rows="10" :id="html_id">{{value}}</textarea>
</template>

<script>
    // FIXME upgrading to CKEditor 5 makes most of this a built-in component: https://ckeditor.com/docs/ckeditor5/latest/builds/guides/integration/frameworks/vuejs.html
    export default {
        props: [
            'html_id',
            'html_name',
            'value',
            'custom_config'
        ],
        mounted() {
            CKEDITOR.plugins.addExternal( 'onchange', '/js/', 'onchangeplugin.js' );
            CKEDITOR.plugins.addExternal( 'eieol_language', '/ckeditor-plugins/eieol_language/', 'plugin.js');
            CKEDITOR.plugins.addExternal( 'html5audio', '/ckeditor-plugins/html5audio/', 'plugin.js');

            let ck_config = {
                toolbar:
                    [
                        {name: 'document', items: ['Source', 'EieolLanguage']},
                        {
                            name: 'clipboard',
                            items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']
                        },
                        {name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'SpellChecker']},
                        {
                            name: 'basicstyles',
                            items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']
                        },
                        {
                            name: 'paragraph',
                            items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-',
                                'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']
                        },
                        {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
                        {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar']},
                        {name: 'styles', items: ['Format', 'FontSize']},
                        {name: 'colors', items: ['TextColor', 'BGColor']},
                        {name: 'insert', items: ['Image','Html5audio']},
                        {name: 'tools', items: ['Maximize']}
                    ],
                contentsCss: '/css/lrcstyle.css',
                disableNativeSpellChecker: false,
                allowedContent: true,
                extraPlugins: 'html5audio,filebrowser,onchange,eieol_language',
                filebrowserUploadUrl: 'http://localhost:4009/admin2/files/upload',
                enterMode: 'CKEDITOR.ENTER_BR',
                entities: false
            };
            if (this.custom_config) {
                ck_config = Object.assign(this.custom_config, ck_config);
            }

            let editor_instance = CKEDITOR.replace(this.$el,ck_config);

            editor_instance.on('change', () => {
                this.$emit('input', editor_instance.getData());
            });
        }
    }
</script>
