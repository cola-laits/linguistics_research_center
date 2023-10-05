<form class="d-flex align-items-center">
    <div class="col-12">
        <label for="language_select" class="form-label">{{__('lexicon.menu.dictionary_jump_label')}}:</label>
        <select class="form-select" id="language_select" onchange="go_to_dictionary(this)">
            <option value="" selected>{{__('lexicon.menu.dictionary_choose_language_prompt')}}</option>
            <optgroup label="{{$lexicon->protolang_name}}">
                <option value="protolang">{{$lexicon->protolang_name}}</option>
            </optgroup>
            @foreach ($lexicon->language_families as $family)
                @foreach ($family->language_sub_families as $subfamily)
                    <optgroup label="{{$family->name}}: {{$subfamily->name}}">
                        @foreach ($subfamily->languages as $language)
                            <option value="{{$language->id}}">{{$language->name}}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            @endforeach
        </select>
    </div>
</form>
