<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>LRC Admin - @yield('title')</title>

        <script src="/js/google_analytics.js"></script>

        <script src="{{ mix('/js/manifest.js') }}"></script>
        <script src="{{ mix('/js/vendor.js') }}"></script>

        <link media="all" type="text/css" rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
        <link media="all" type="text/css" rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
        <link media="all" type="text/css" rel="stylesheet" href="/css/adminstyle.css">
        <link media="all" type="text/css" rel="stylesheet" href="/css/jquery.tagsinput.css">
        <link media="all" type="text/css" rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">

        <style>
            body {
                font-family: Times New Roman, Times, serif;
            }
        </style>

        @yield('head_extra')
    </head>
    <body onload="top.scrollTo(0,0)">

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <a class="navbar-brand" href="/admin">Admin</a>

        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">EIEOL <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/admin2/eieol_series">Series</a></li>
                        <li><a href="/admin2/eieol_language">Languages</a></li>
                    </ul>
                </li>
                @if (\Illuminate\Support\Facades\Auth::user()->isAdmin())
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Lexicon <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/admin2/lexicon#/etyma">Etyma</a></li>
                            <li><a href="/admin2/lexicon#/reflex">Reflex</a></li>
                            <li><a href="/admin2/lexicon#/reflex_entry">Reflex → Entry</a></li>
                            <li><a href="/admin2/lexicon#/reflex_pos">Reflex → Part of Speech</a></li>
                            <li><a href="/admin2/lexicon#/sem_cat">Semantic Category</a></li>
                            <li><a href="/admin2/lexicon#/sem_field">Semantic Field</a></li>
                            <li><a href="/admin2/lexicon#/lang_fam">Language Family</a></li>
                            <li><a href="/admin2/lexicon#/lang_subfam">Language Sub Family</a></li>
                            <li><a href="/admin2/lexicon#/lang">Language</a></li>
                            <li><a href="/admin2/lexicon#/source">Source</a></li>
                            <li><a href="/admin2/lexicon#/pos">Part of Speech</a></li>
                        </ul>
                    </li>
                <li><a href="/admin2/user">Users</a></li>
                <li><a href="/admin2/page">Pages</a></li>
                @endif
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/">Back to Site</a></li>
                <li><form class="navbar-form" method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="btn btn-default">Log Out</button>
                    </form></li>
            </ul>
        </div>
        </div>
    </nav>

@include('eieol_lesson.confirm_delete')

    <div class="container-fluid v-cloak" id="admin_app">
        @yield('content')
    </div>

    <script src="/js/vue-search-select.js"></script>
    <script src="/ckeditor/ckeditor-4.4.5-full/ckeditor.js"></script>

    <script src="{{ mix('/js/admin.js') }}"></script>

    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

    <script src="/js/jquery.tagsinput.js"></script>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>


    <script type="text/javascript">
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
        axios.interceptors.response.use(function(response) {
            return response;
        }, function(error) {
            alert("Sorry, an error occurred.  Refresh the page and try again.");
            return Promise.reject(error);
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function(){
            //generic delete confirmation
            $(".delete").click(function(e) {
                e.preventDefault();
                var $form=$(this).closest('form');

                $("#delete_confirm").modal('show')
                    .one('click', '#delete_confirmed', function (e) {
                        $form.trigger('submit');
                    });
            });

        });

    </script>

    @yield('foot_extra')

    <br><br>
    </body>
</html>	


