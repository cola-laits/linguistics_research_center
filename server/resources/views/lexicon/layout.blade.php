<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link href="/assets/bootstrap/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/lexicon.css" rel="stylesheet">

    <script>
        window.lexicon_slug = "{{$lexicon->slug}}";

        function choose_selector_type(type) {
            var selection_links = document.getElementsByClassName('selector_type_link');
            for (var i=0;i<selection_links.length;i++) {
                selection_links.item(i).classList.remove('selected');
            }
            var selected_selection_link = document.getElementById('selector_type_link_'+type);
            if (selected_selection_link) {
                selected_selection_link.classList.add('selected')
            }

            var selectors = document.getElementsByClassName('selector_type');
            for (var i=0; i<selectors.length; i++ ) {
                var selector = selectors[i];
                if (selector.id === 'selector_type_'+type) {
                    selector.style.display = 'block';
                } else {
                    selector.style.display = 'none';
                }
            }

            document.getElementById('sidebar').scrollTo(0, 0);
        }

        function show_selected_sidebar_item(element_to_highlight) {
            var num_lis_above_highlighted = 4;
            var scrollToPosn = element_to_highlight.offsetTop
                - document.getElementById('sidebar-header').offsetHeight
                - (element_to_highlight.offsetHeight * num_lis_above_highlighted);
            document.getElementById('sidebar').scrollTo(0, scrollToPosn);
        }

        function highlight_sidebar(type, id) {
            document.addEventListener('DOMContentLoaded', function() {
                var sidebar_list = document.getElementById('selector_type_'+type);
                if (!sidebar_list) { return; }
                var element_to_highlight = sidebar_list.querySelector('[data-sidebar-id="'+id+'"]');
                if (!element_to_highlight) { return; }
                element_to_highlight.classList.add('highlighted');

                // part of an accordion?  open that accordion.
                var accordion = element_to_highlight.closest('.accordion-collapse');
                if (accordion) {
                    accordion.addEventListener('shown.bs.collapse', function () {
                        show_selected_sidebar_item(element_to_highlight);
                    });
                    new bootstrap.Collapse(accordion, {show: true});
                } else {
                    show_selected_sidebar_item(element_to_highlight);
                }
            });
        }

        function go_to_dictionary(select) {
            if (select.selectedIndex===0) {
                return;
            }
            var id = select.options[select.selectedIndex].value;
            if (id==='protolang') {
                document.location.href = "/lexicon/" + window.lexicon_slug;
            } else {
                document.location.href = "/lexicon/" + window.lexicon_slug + "/language/" + id;
            }
        }

        function search_word_sidebar(value) {
            value = value.trim();
            var items = document.getElementById('sidebar-word-list').getElementsByTagName('li');
            for (var i=0;i<items.length;i++) {
                var item = items[i];
                if (item.innerText.toLowerCase().indexOf(value.toLowerCase()) === -1) {
                    item.style.display = 'none';
                } else {
                    item.style.display = '';
                }
            }
        }

        function clear_word_search() {
            document.getElementById('search_word_text').value='';
            search_word_sidebar('');
        }

        function search_category_sidebar(value) {
            value = value.trim();
            var items = document.getElementById('accordionCategory').getElementsByClassName('accordion-item');
            for (var i=0;i<items.length;i++) {
                var item = items[i];
                var fields = item.getElementsByTagName('li');
                var num_fields_shown = 0;
                Array.from(fields).forEach(function (field) {
                    if (field.getElementsByTagName('a')[0].innerText.toLowerCase().indexOf(value.toLowerCase()) === -1) {
                        field.style.display = 'none';
                    } else {
                        field.style.display = '';
                        num_fields_shown++;
                    }
                });

                if (num_fields_shown > 0) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
                if (value==='') {
                    bootstrap.Collapse.getOrCreateInstance(item.getElementsByClassName('accordion-collapse')[0]).hide();
                } else {
                    bootstrap.Collapse.getOrCreateInstance(item.getElementsByClassName('accordion-collapse')[0]).show();
                }
            }
        }

        function clear_category_search() {
            document.getElementById('search_category_text').value='';
            search_category_sidebar('');
        }

        document.addEventListener('DOMContentLoaded', function() {
            var items = document.getElementById('accordionCategory').getElementsByClassName('accordion-item');
            for (var i=0;i<items.length;i++) {
                var item = items[i];
                bootstrap.Collapse.getOrCreateInstance(item.getElementsByClassName('accordion-collapse')[0], {toggle:false});
            }
        })
    </script>

    @include('analytics_bug')
</head>
<body>

<div class="container-fluid">
<div class="mainrow row">
<div class="col-lg-9 col-md-12 p-3" style="height:100vh;overflow-y:scroll;">
    <header class="d-flex align-items-center justify-content-between pb-3 mb-5 border-bottom">
        <span>
        <a href="/" class="text-dark text-decoration-none">
            <img height="43" src="/images/lrc-banner.png" alt="Linguistics Research Center - The University of Texas at Austin">
        </a>
        <a href="/lexicon/{{$lexicon->slug}}" class="text-dark text-decoration-none">
            <span class="header-lexiconname fs-4">{{$lexicon->name}}</span>
        </a>
        </span>
        <form class="d-flex align-items-center">
            <div class="col-12">
                <label for="language_select" class="form-label">Jump to a dictionary:</label>
                <select class="form-select" id="language_select" onchange="go_to_dictionary(this)">
                    <option value="" selected>choose a language...</option>
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
    </header>

    <main>
        @yield('content')
    </main>

    <div><pre style="background-color:#999999;padding:10px;margin:10px;">
    Right now, mostly just focusing on getting all the right information on the page, and working out page-to-page navigation.

    TODO:
        all pages:
            display data (reflexes on etyma pages, cognates on reflex pages, items in sidebar, etc) in some sensible order other than database-id order
        search page:
            completely missing for now (probably need actual SEMITILEX data first)
        semantic field page:
            descendent words missing - what's the right UI here?
        general cleanup / prep for release:
            add page-specific [title] tags
            investigate and add SEO tags
            general style/UI/UX once-over
            responsive (tablet/mobile) testing
    </pre></div>
</div>

<div id="sidebar" class="sidebar col-lg-3 col-md-12 p-0" style="height:100vh;overflow-y:scroll">
    <div id="sidebar-header" class="sidebar-header">
        <p>
            <a href="#" class="selector_type_link{{$selected_sidebar=='headword' ? ' selected' : ''}}"
               onclick="choose_selector_type('headword');return false;" id="selector_type_link_headword">Headwords</a>
            |
            <a href="#" class="selector_type_link{{$selected_sidebar=='category' ? ' selected' : ''}}"
               onclick="choose_selector_type('category');return false;" id="selector_type_link_category">Categories</a>
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
                        <button type="submit" class="btn btn-light">Search</button>
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
                        <button type="submit" class="btn btn-light">Search</button>
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
</div>
</div>

<script src="/assets/bootstrap/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
