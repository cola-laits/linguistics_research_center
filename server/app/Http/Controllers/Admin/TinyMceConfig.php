<?php

namespace App\Http\Controllers\Admin;

trait TinyMceConfig
{
    protected function getDefaultTinyMceOptions()
    {
        return [
            'plugins' => [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table directionality',
                'emoticons template paste textpattern imagetools codesample toc'
            ],
            'height' => 500,
            'branding' => false,
            'image_caption' => true,
            'images_upload_url' => '/admin2/files/upload?uploader=tinymce',
            'menubar' => 'edit insert view format table',
            'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
            'image_advtab' => true,
        ];
    }
}
