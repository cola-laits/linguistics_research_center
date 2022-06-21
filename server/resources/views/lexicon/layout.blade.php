<html>
<head>
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

<h1>FIXME: temporary page layout - things are ugly on purpose</h1>

<h2 style="background-color:lightpink"><a href="/">UT LRC</a>: <a href="/lexicon/{{$lexicon->slug}}">{{$lexicon->name}}</a></h2>

<div style="background-color:lightblue; width:30%; float:right;">
    @yield('search-sidebar')
</div>

<div style="background-color:lightgrey">
@yield('content')
</div>



</body>
</html>
