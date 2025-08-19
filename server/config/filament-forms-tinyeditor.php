<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Profiles
    |--------------------------------------------------------------------------
    |
    | You can add as many as you want of profiles to use it in your application.
    |
    */

    'profiles' => [

        'default' => [
            'plugins' => 'advlist autoresize codesample directionality emoticons fullscreen hr image imagetools link lists media table toc wordcount',
            'toolbar' => 'undo redo removeformat | formatselect fontsizeselect | bold italic | rtl ltr | alignjustify alignright aligncenter alignleft | numlist bullist | forecolor backcolor | blockquote table toc hr | image link media codesample emoticons | wordcount fullscreen',
            'upload_directory' => null,
        ],

        'simple' => [
            'plugins' => 'autoresize directionality emoticons link wordcount',
            'toolbar' => 'removeformat | bold italic | rtl ltr | link emoticons',
            'upload_directory' => null,
        ],

        'page' => [
            'plugins' => 'advlist autoresize codesample fullscreen hr image imagetools link lists table wordcount',
            'toolbar' => 'undo redo removeformat | formatselect fontsizeselect | bold italic | alignjustify alignright aligncenter alignleft | numlist bullist | blockquote table hr | image link',
            'upload_directory' => null,
        ],

        // FIXME not implemented yet
        'issue' => [
            'plugins' => 'code searchreplace lists advlist link anchor table image',
            'toolbar' =>  'code | undo redo | bold italic removeformat | '
                . 'numlist bullist | outdent indent | link unlink | table hr | '
                . 'formatselect fontsizeselect | image | '
                . ' blockquote | alignleft aligncenter alignright alignjustify ',
            'upload_directory' => null,
        ],

        'eieol_lesson' => [
            'plugins' => 'code searchreplace lists advlist link anchor table image directionality',
            'toolbar' =>  'code language lrc_charsequences | undo redo | bold italic underline strikethrough subscript superscript | removeformat | '
               . 'numlist bullist | outdent indent | link unlink | table hr | '
               . 'formatselect fontsizeselect | image | '
               . ' blockquote | alignleft aligncenter alignright alignjustify ',
            'upload_directory' => null,
        ]

        /*
        |--------------------------------------------------------------------------
        | Custom Configs
        |--------------------------------------------------------------------------
        |
        | If you want to add custom configurations to directly tinymce
        | You can use custom_configs key as an array
        |
        */

        /*
          'default' => [
            'plugins' => 'advlist autoresize codesample directionality emoticons fullscreen hr image imagetools link lists media table toc wordcount',
            'toolbar' => 'undo redo removeformat | formatselect fontsizeselect | bold italic | rtl ltr | alignjustify alignright aligncenter alignleft | numlist bullist | forecolor backcolor | blockquote table toc hr | image link media codesample emoticons | wordcount fullscreen',
            'custom_configs' => [
                'allow_html_in_named_anchor' => true,
                'link_default_target' => '_blank',
                'codesample_global_prismjs' => true,
                'image_advtab' => true,
                'image_class_list' => [
                  [
                    'title' => 'None',
                    'value' => '',
                  ],
                  [
                    'title' => 'Fluid',
                    'value' => 'img-fluid',
                  ],
              ],
            ]
        ],
        */

    ],
];
