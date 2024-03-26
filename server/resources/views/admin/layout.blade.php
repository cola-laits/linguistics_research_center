<!DOCTYPE html>
<html lang='en'>
    <head>
        @if (env('SENTRY_JS_DSN'))
            <script src="https://browser.sentry-cdn.com/5.6.3/bundle.min.js" integrity="sha384-/Cqa/8kaWn7emdqIBLk3AkFMAHBk0LObErtMhO+hr52CntkaurEnihPmqYj3uJho" crossorigin="anonymous"></script>
            <script>
                Sentry.init({ dsn: '{{env('SENTRY_JS_DSN')}}' });
            </script>
        @endif

        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>LRC Admin - @yield('title')</title>

        @vite(['resources/sass/admin.scss', 'resources/js/admin.js'])

        <link media="all" type="text/css" rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
        <link media="all" type="text/css" rel="stylesheet" href="/css/adminstyle.css">

        <style>
            body {
                font-family: Times New Roman, Times, serif;
            }
        </style>

        @yield('head_extra')
    </head>
    <body onload="top.scrollTo(0,0)">

    <nav class="navbar navbar-light navbar-expand-md bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/admin">Admin</a>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="/admin2/issues">Issues <span
                            @class(['badge', 'rounded-pill', 'd-none'=>($numOpenIssues==0), 'bg-warning'=>($numOpenIssues>0)])
                            >{{$numOpenIssues}}</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin2/eieol_series">EIEOL</a></li>
                    @if (\Illuminate\Support\Facades\Auth::user()->isAdmin())
                        <li class="dropdown nav-item">
                            <a href="/admin_mgr" class="nav-link" role="button" aria-haspopup="true" aria-expanded="false">Site Management <span class="caret"></span></a>
                        </li>
                    @endif
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="/">Back to Site</a></li>
                    <li class="nav-item"><form class="navbar-form" method="POST" action="/logout">
                            @csrf
                            <button type="submit" class="btn btn-secondary">Log Out</button>
                        </form></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        @yield('content')
    </div>

    <script src="https://cdn.ckeditor.com/4.14.1/full-all/ckeditor.js"></script>

    <script type="text/javascript">
        if (window.axios) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            window.axios.interceptors.response.use(function (response) {
                return response;
            }, function (error) {
                alert("Sorry, an error occurred.  Refresh the page and try again.");
                return Promise.reject(error);
            });
        }

    </script>

    @yield('foot_extra')

    <br><br>
    </body>
</html>


