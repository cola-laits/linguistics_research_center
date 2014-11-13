<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>LRC Admin - @yield('title')</title>
 
        {{ HTML::style('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css') }}
        {{ HTML::style('//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css') }}
        
        <style>
            body {
                margin-top: 5%;
            }
        </style>
    </head>
    <body>
        {{ HTML::link('logout', 'Logout', array('title' => 'Logout' )) }}
        <div class='container-fluid'>
            <div class='row'>
                @yield('content')
            </div>
        </div>
    </body>
</html>


