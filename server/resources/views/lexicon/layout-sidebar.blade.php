<div id="sidebar" class="sidebar" style="height:100vh;overflow-y:scroll">
    <div id="sidebar-header" class="sidebar-header">
        <p>
            <a href="#" class="selector_type_link{{$selected_sidebar=='headword' ? ' selected' : ''}}"
               onclick="choose_selector_type('headword');return false;" id="selector_type_link_headword">{{__('lexicon.sidebar.title_headwords')}}</a>
            |
            <a href="#" class="selector_type_link{{$selected_sidebar=='category' ? ' selected' : ''}}"
               onclick="choose_selector_type('category');return false;" id="selector_type_link_category">{{__('lexicon.sidebar.title_categories')}}</a>
        </p>
    </div>
    <div id="sidebar-content" class="sidebar-content">
        <div class="selector_type" id="selector_type_headword"@if ($selected_sidebar!=='headword') style="display:none;"@endif>
            <div class="sidebar-search-area">
                <form id="search_form" onsubmit="search_word_sidebar(this.text.value);return false;" class="row row-cols-lg-auto align-items-center">
                    <div class="col-9">
                        <div class="input-group">
                            <input type="text" id="search_word_text" name="text" class="form-control">
                            <button class="btn btn-outline-secondary" style="background-color:white;" type="button" onclick="clear_word_search()">
                                <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.72 5.72a.75.75 0 011.06 0L12 10.94l5.22-5.22a.75.75 0 111.06 1.06L13.06 12l5.22 5.22a.75.75 0 11-1.06 1.06L12 13.06l-5.22 5.22a.75.75 0 01-1.06-1.06L10.94 12 5.72 6.78a.75.75 0 010-1.06z"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="col-3">
                        <button type="submit" class="btn btn-light">{{__('lexicon.sidebar.button_label_search')}}</button>
                    </div>
                </form>
            </div>
            <ul style="padding-left: 1rem;" id="sidebar-word-list">
                @yield('search-item-list')
            </ul>
        </div>
        <div class="selector_type" id="selector_type_category"@if ($selected_sidebar!=='category') style="display:none;"@endif>
            <div class="sidebar-search-area">
                <form id="search_form" onsubmit="search_category_sidebar(this.text.value);return false;" class="row row-cols-lg-auto align-items-center">
                    <div class="col-9">
                        <div class="input-group">
                            <input type="text" id="search_category_text" name="text" class="form-control">
                            <button class="btn btn-outline-secondary" style="background-color:white;" type="button" onclick="clear_category_search()">
                                <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.72 5.72a.75.75 0 011.06 0L12 10.94l5.22-5.22a.75.75 0 111.06 1.06L13.06 12l5.22 5.22a.75.75 0 11-1.06 1.06L12 13.06l-5.22 5.22a.75.75 0 01-1.06-1.06L10.94 12 5.72 6.78a.75.75 0 010-1.06z"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="col-3">
                        <button type="submit" class="btn btn-light">{{__('lexicon.sidebar.button_label_search')}}</button>
                    </div>
                </form>
            </div>
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
</div>
