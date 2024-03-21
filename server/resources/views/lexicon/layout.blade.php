<!doctype html>
<html lang="{{App::getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link href="/assets/bootstrap/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.la.utexas.edu/fontawesome/fontawesome-pro-5.12.0/css/all.min.css" rel="stylesheet">
    <link href="/css/lexicon.css" rel="stylesheet">

    @yield('header_extras')

    <script>
        window.lexicon_slug = "{{$lexicon->slug}}";

        function choose_selector_type(type) {
            var selection_links = document.getElementsByClassName('selector_type_link');
            for (var i=0;i<selection_links.length;i++) {
                selection_links.item(i).classList.remove('active');
            }
            var selected_selection_link = document.getElementById('selector_type_link_'+type);
            if (selected_selection_link) {
                selected_selection_link.classList.add('active')
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

        function show_selected_sidebar_item(sidebar_list, element_to_highlight) {
            if (sidebar_list.style.display === "none") {
                return;
            }
            var num_lis_above_highlighted = 4;
            var scrollToPosn = element_to_highlight.offsetTop
                - document.getElementById('sidebar-header').offsetHeight
                - (element_to_highlight.offsetHeight * num_lis_above_highlighted);
            document.getElementById('sidebar-content').scrollTo(0, scrollToPosn);
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
                        show_selected_sidebar_item(sidebar_list, element_to_highlight);
                    });
                    new bootstrap.Collapse(accordion, {show: true});
                } else {
                    show_selected_sidebar_item(sidebar_list, element_to_highlight);
                }
            });
        }

        function go_to_dictionary(select) {
            if (select.selectedIndex===0) {
                return;
            }
            var id = select.options[select.selectedIndex].value;
            if (id==='protolang') {
                document.location.href = "/lexicon/" + window.lexicon_slug + '/language/protolanguage';
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

        function get_directly_contained_text(element) {
            let text = '';
            for (var i = 0; i < element.childNodes.length; ++i)
                if (element.childNodes[i].nodeType === Node.TEXT_NODE)
                    text += element.childNodes[i].textContent;
            return text;
        }

        function search_language_sidebar(value) {
            value = value.trim();
            var items = document.getElementById('sidebar-word-list').getElementsByTagName('li');
            for (var i=0;i<items.length;i++) {
                var item = items[i];
                let this_item_text = item.textContent;
                let this_item_parent_text = get_directly_contained_text(item.parentElement.parentElement);
                let is_matched_item = this_item_text.toLowerCase().indexOf(value.toLowerCase()) !== -1;
                let is_child_of_matched_item = this_item_parent_text.toLowerCase().indexOf(value.toLowerCase()) !== -1;
                console.log([this_item_text, this_item_parent_text, is_matched_item, is_child_of_matched_item]);
                if (is_matched_item || is_child_of_matched_item) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            }
        }

        function clear_language_search() {
            document.getElementById('search_language_text').value='';
            search_language_sidebar('');
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('accordionCategory')) {
                var items = document.getElementById('accordionCategory').getElementsByClassName('accordion-item');
                for (var i = 0; i < items.length; i++) {
                    var item = items[i];
                    bootstrap.Collapse.getOrCreateInstance(item.getElementsByClassName('accordion-collapse')[0], {toggle: false});
                }
            }
        })
    </script>

    @include('analytics_bug')
</head>
<body>

<div class="d-flex">
<div class="container-fluid" style="height:100vh;overflow-y:scroll;padding:15px;width:100%;">
    <header class="mb-4">
        <div class="d-flex flex-wrap justify-content-between py-3 border-bottom">
            <div class="d-flex">
                <div>
                    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                        <img height="43" src="{{__('lexicon.header.banner.image_url')}}" alt="{{__('lexicon.header.banner.alt_text')}}">
                    </a>
                </div>
            </div>

            <div class="d-flex align-items-center mb-3 mb-md-0">

            </div>

            <div>

                @if (count($lexicon->getViewerLangsArray()) > 1)
                    <div class="dropdown">
                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            {{$lexicon->getDisplayTextViewerLang(App::getLocale())}}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            @foreach ($lexicon->getViewerLangsArray() as $viewer_lang)
                                <a class="dropdown-item" href="/lexicon/{{$lexicon->slug}}?switchlang={{$viewer_lang}}">{{$lexicon->getDisplayTextViewerLang($viewer_lang)}}</a>
                            @endforeach
                        </div>
                    </div>
                @else
                    @if (App::getLocale() !== 'en')
                        <div class="d-flex align-items-center mb-3 mb-md-0 me-md-auto">
                            {{-- Offer an emergency 'back to English' button in case you land on a lexicon without language choices --}}
                            <button type="button" class="btn btn-primary"
                                    onclick="document.location.href='/lexicon/{{$lexicon->slug}}?switchlang=en'"
                            >{{$lexicon->getDisplayTextViewerLang('en')}}</button>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="border-bottom">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/lexicon/{{$lexicon->slug}}">{{__('lexicon.header.lex_home.text', ['lexicon_name'=>$lexicon->name])}}</a></li>
                @foreach ($breadcrumb_segments??[] as $segment)
                    @if (isset($segment['url']))
                        <li class="breadcrumb-item"><a href="{{$segment['url']}}">{{$segment['text']}}</a></li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page">{{$segment['text']}}</li>
                    @endif
                @endforeach
            </ol>
        </nav>
    </header>

    <main>
        <h1>@yield('page-title')</h1>

        @yield('content')
    </main>

</div>

    @yield('sidebar')

</div>

<script src="/assets/bootstrap/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
