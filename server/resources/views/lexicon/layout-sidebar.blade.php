<div id="sidebar" class="sidebar d-flex flex-column">
    <div id="sidebar-header" class="sidebar-header d-flex flex-column">
        <div style="padding-bottom: 1rem;">
            <form class="d-flex align-items-center">
                <div class="col-12">
                    <label for="language_select" class="form-label">{{__('lexicon.menu.dictionary_jump_label')}}:</label>
                    <select class="form-select" id="language_select" onchange="go_to_dictionary(this)">
                        <option value="" selected>{{__('lexicon.menu.dictionary_choose_language_prompt')}}</option>
                        <optgroup label="{{$lexicon->protolang_name}}">
                            <option value="protolang" @selected(isset($protolang))>{{$lexicon->protolang_name}}</option>
                        </optgroup>
                        @foreach ($lexicon->language_families as $family)
                            @foreach ($family->language_sub_families as $subfamily)
                                <optgroup label="{{$family->name}}: {{$subfamily->name}}">
                                    @foreach ($subfamily->languages as $s_language)
                                        <option value="{{$s_language->id}}" @selected(isset($language) && $language->id===$s_language->id)>{{$s_language->name}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a @class(["nav-link", "selector_type_link", "active" => $selected_sidebar=='headword'])
                   href="#"
                   onclick="choose_selector_type('headword');return false;" id="selector_type_link_headword">{{__('lexicon.sidebar.title_headwords')}}</a>
            </li>
            <li class="nav-item">
                <a @class(["nav-link", "selector_type_link", "active" => $selected_sidebar=='category'])
                    href="#"
                    onclick="choose_selector_type('category');return false;" id="selector_type_link_category">{{__('lexicon.sidebar.title_categories')}}</a>
            </li>
        </ul>
    </div>

    <div id="sidebar-content" class="sidebar-content flex-grow-1">
        <div class="selector_type" id="selector_type_headword"@if ($selected_sidebar!=='headword') style="display:none;"@endif>
            <ul style="padding-left: 1rem;" id="sidebar-word-list">
                @yield('search-item-list')
            </ul>
        </div>
        <div class="selector_type" id="selector_type_category"@if ($selected_sidebar!=='category') style="display:none;"@endif>
            <div class="accordion" id="accordionCategory">
                @foreach ($lexicon->semantic_categories as $category)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="accordion_category_heading_{{$category->id}}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion_category_collapse_{{$category->id}}" aria-expanded="true" aria-controls="accordion_category_collapse_{{$category->id}}">
                                {{$category->text}}
                            </button>
                        </h2>
                        <div id="accordion_category_collapse_{{$category->id}}" class="accordion-collapse collapse" aria-labelledby="accordion_category_heading_{{$category->id}}">
                            <div class="accordion-body">
                                <ul>
                                    @foreach ($category->semantic_fields as $field)
                                        <li data-sidebar-id="{{$field->id}}"><a href="/lexicon/{{$lexicon->slug}}/field/{{$field->id}}">{{$field->text}}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="sidebar-footer">
        <div class="selector_type p-1" id="selector_type_headword"@if ($selected_sidebar!=='headword') style="display:none;"@endif>
            <div class="sidebar-search-area">
                <form id="search_form" onsubmit="search_word_sidebar(this.text.value);return false;" class="align-items-center d-flex justify-content-between">
                    <div>
                        <div class="input-group">
                            <input type="text"
                                   id="search_word_text"
                                   name="text"
                                   class="form-control"
                                   onkeyup="search_word_sidebar(this.value)"
                                   placeholder="{{__('lexicon.sidebar.search_placeholder_headwords')}}">
                            <button class="btn btn-outline-secondary" style="background-color:white;" type="button" onclick="clear_word_search()">
                                <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.72 5.72a.75.75 0 011.06 0L12 10.94l5.22-5.22a.75.75 0 111.06 1.06L13.06 12l5.22 5.22a.75.75 0 11-1.06 1.06L12 13.06l-5.22 5.22a.75.75 0 01-1.06-1.06L10.94 12 5.72 6.78a.75.75 0 010-1.06z"/></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-light">{{__('lexicon.sidebar.button_label_search')}}</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="selector_type p-1" id="selector_type_category"@if ($selected_sidebar!=='category') style="display:none;"@endif>
            <div class="sidebar-search-area">
                <form id="search_form" onsubmit="search_category_sidebar(this.text.value);return false;" class="align-items-center d-flex justify-content-between">
                    <div>
                        <div class="input-group">
                            <input type="text"
                                   id="search_category_text"
                                   name="text"
                                   class="form-control"
                                   placeholder="{{__('lexicon.sidebar.search_placeholder_categories')}}">
                            <button class="btn btn-outline-secondary" style="background-color:white;" type="button" onclick="clear_category_search()">
                                <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.72 5.72a.75.75 0 011.06 0L12 10.94l5.22-5.22a.75.75 0 111.06 1.06L13.06 12l5.22 5.22a.75.75 0 11-1.06 1.06L12 13.06l-5.22 5.22a.75.75 0 01-1.06-1.06L10.94 12 5.72 6.78a.75.75 0 010-1.06z"/></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-light">{{__('lexicon.sidebar.button_label_search')}}</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="d-flex justify-content-center pb-2">
            <a href="/lexicon/{{$lexicon->slug}}/data">
                <i class="far fa-table"></i> <span class="ps-1">{{__('lexicon.pages.home.search_lexicon_link_text')}}</span>
            </a>
        </div>
    </div>
</div>
