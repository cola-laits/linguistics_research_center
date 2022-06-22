<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link href="/assets/bootstrap/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/lexicon.css" rel="stylesheet">

    <script>
        function choose_selector_type(type) {
            var selectors = document.getElementsByClassName('selector_type');
            for (var i=0; i<selectors.length; i++ ) {
                var selector = selectors[i];
                if (selector.id === 'selector_type_'+type) {
                    selector.style.display = 'block';
                } else {
                    selector.style.display = 'none';
                }

            }
        }
    </script>
</head>
<body>

<div class="container-fluid">
<div class="mainrow row">
<div class="col-lg-9 col-md-12 p-3">
    <header class="d-flex align-items-center pb-3 mb-5 border-bottom">
        <a href="/" class="d-flex align-items-center text-dark text-decoration-none">
            <img height="43" src="/images/lrc-banner.png" alt="Linguistics Research Center - The University of Texas at Austin">
        </a>
        <a href="/lexicon/{{$lexicon->slug}}" class="d-flex align-items-center text-dark text-decoration-none">
            <span class="header-lexiconname fs-4">{{$lexicon->name}}</span>
        </a>
    </header>

    <main>
        @yield('content')
    </main>

    <p><pre>
        TODO:
        add page-specific [title] tags
        add a way of navigating to a language page from anywhere
        sidebar:
           highlight the current option for this page if it has one
           categories - disclosure triangle on categories, revealing fields
        display etyma with asterisk prefix
        display data (reflexes on etyma pages, cognates on reflex pages, items in sidebar, etc) in some sensible order other than database-id order
    </pre></p>
</div>

<div class="sidebar col-lg-3 col-md-12 p-3">
    @yield('search-sidebar')
</div>
</div>
</div>

<script src="/assets/bootstrap/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
