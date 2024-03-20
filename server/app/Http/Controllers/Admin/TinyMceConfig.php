<?php

namespace App\Http\Controllers\Admin;

trait TinyMceConfig
{
    protected function getDefaultTinyMceOptions($style='default') : array
    {
        $config = [
            'plugins' => [
                'advlist',
                'autolink',
                'lists',
                'link',
                'image',
                'charmap',
                'preview',
                'anchor',
                'pagebreak',
                'searchreplace',
                'wordcount',
                'visualblocks',
                'visualchars',
                'code',
                'fullscreen',
                'insertdatetime',
                'media',
                'nonbreaking',
                'save',
                'table',
                'directionality',
                'emoticons',
                'template',
                'codesample',
            ],
            'height' => 500,
            'branding' => false,
            'image_caption' => true,
            'images_upload_url' => '/admin2/files/upload?uploader=tinymce',
            'menubar' => 'edit insert view format table',
            'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
            'image_advtab' => true,
            'file_picker_callback'=>null,
        ];
        if ($style === 'lexicon') {
            $config['content_css'] = '/css/lexicon.css';
        }
        return $config;
    }
}
