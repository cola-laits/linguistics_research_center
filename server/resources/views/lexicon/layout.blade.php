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
            document.location.href = "/lexicon/"+window.lexicon_slug+"/language/"+id;
        }
    </script>
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
    TODO:
        all pages:
            sidebar search bar (should filter currently-displayed list, i guess?)
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
    @yield('search-sidebar')
</div>
</div>
</div>

<script src="/assets/bootstrap/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
