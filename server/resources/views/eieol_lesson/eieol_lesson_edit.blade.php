@extends('admin_layout')

@section('title') Edit Lesson @stop

@section('foot_extra')
    <script>
        window.onbeforeunload = function() {
            if (document.querySelectorAll('form[dirty]').length > 0) {
                return 'You have unsaved changes!  Would you like to leave this page anyway?';
            }
        };
    </script>
@endsection

@section('content')
    <div id="admin_app">

    <lesson-editor
    :init_lesson="{{ json_encode($lesson) }}"
    :init_glossed_texts="{{ json_encode($glossed_texts) }}"
    :init_grammars="{{ json_encode($grammars) }}"
    :init_etymas="{{ json_encode($etymas) }}"
    :init_issues="{{ json_encode($issues) }}"
    :init_ckeditor_customization="{language_list :
[
@foreach ($series_languages as $series_language)
    '{{$series_language}}',
@endforeach
],
language_lang : '{{$lesson->language->lang_attribute}}',
specialChars : [ {{ $lesson->language->custom_keyboard_layout }}]
}"
    :init_custom_keyboard_layout="[ {{ $lesson->language->custom_keyboard_layout }} ]"
    :focus="'{{app('request')->input('focus') ?: ''}}'"
>

</lesson-editor>

    </div>
@endsection
