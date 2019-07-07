@extends('admin_layout')
 
@section('title') Edit Lesson @stop

@section('head_extra')
    <script type="text/javascript">
        // FIXME are these needed anymore?
        window.lesson_language_id = {{$lesson->language_id}};
        @if (Auth::user()->isAdmin())
            window.isAdmin = true;
        @else
            window.isAdmin = false;
        @endif
    </script>
@endsection

@section('foot_extra')
    <script>
        window.onbeforeunload = function() {
            if ($("form[dirty]").length > 0) {
                return 'You have unsaved changes!  Would you like to leave this page anyway?';
            }
        };

    </script>
@endsection

@section('content')
<lesson-editor
    :init_lesson="{{ json_encode($lesson) }}"
    :init_languages="{{ json_encode($languages) }}"
    :init_glossed_texts="{{ json_encode($glossed_texts) }}"
    :init_grammars="{{ json_encode($grammars) }}"
    :init_etymas="{{ json_encode($etymas) }}"
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
    :init_is_user_admin="{{Auth::user()->isAdmin()}}"
    :focus="'{{app('request')->input('focus') ?: ''}}'"
>

</lesson-editor>
@endsection
