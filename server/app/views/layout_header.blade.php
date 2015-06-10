<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" class="no-js" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0 maximum-scale=1" name="viewport"/>

<!-- FAVICONS -->
<link href="//www.utexas.edu/cola/_internal/images/favicons/favicon.ico" rel="icon"/>
<link href="//www.utexas.edu/cola/_internal/images/favicons/apple-touch-icon-180x180.png" rel="apple-touch-icon" sizes="180x180"/>
<link href="//www.utexas.edu/cola/_internal/images/favicons/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152"/>
<!--[if IE]><link rel="shortcut icon" href="//www.utexas.edu/cola/_internal/images/favicons/favicon.ico" />
<![endif]-->
<meta content="//www.utexas.edu/cola/_internal/images/favicons/apple-touch-icon-144x144.png" name="msapplication-TileImage"/> 
<meta content="#ffffff" name="msapplication-TileColor"/>
<meta content="UT Austin" name="apple-mobile-web-app-title"/>
<link href="//www.utexas.edu/cola/_internal/images/favicons/apple-touch-icon-144x144.png" rel="apple-touch-icon" sizes="144x144"/>
<link href="//www.utexas.edu/cola/_internal/images/favicons/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120"/>
<link href="//www.utexas.edu/cola/_internal/images/favicons/apple-touch-icon.png" rel="apple-touch-icon"/>
<link href="//www.utexas.edu/cola/_internal/images/favicons/apple-touch-icon-57x57.png" rel="apple-touch-icon" sizes="57x57"/>
<link href="//www.utexas.edu/cola/_internal/images/favicons/apple-touch-icon-60x60.png" rel="apple-touch-icon" sizes="60x60"/>
<link href="//www.utexas.edu/cola/_internal/images/favicons/apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76"/>
<link href="//www.utexas.edu/cola/_internal/images/favicons/apple-touch-icon-72x72.png" rel="apple-touch-icon" sizes="72x72"/>
<link href="//www.utexas.edu/cola/_internal/images/favicons/apple-touch-icon-114x114.png" rel="apple-touch-icon" sizes="114x114"/>

<title>@section('title') The Linguistics Research Center @show</title>

<meta content="Thu, 07 May 2015 05:12:57 -0500" name="date"/>

<!-- CSS LOADING -->
<link href="//www.utexas.edu/cola/_internal/css/app.css" rel="stylesheet" type="text/css"/>
<link href="//fonts.googleapis.com/css?family=Roboto:400,300,700,700italic,400italic|Roboto+Condensed:400,300" rel="stylesheet" type="text/css"/>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

	{{ HTML::style('css/lrcstyle.css') }}
	{{ HTML::style('//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css') }}
	{{ HTML::style('//cdn.datatables.net/1.10.6/css/jquery.dataTables.min.css') }}
	{{ HTML::style('css/demo_table_jui.css') }}

<!--  "Must Have in the Header" Javascript -->
<script src="//jwpsrv.com/library/1O4izvC8EeKppRIxOQulpA.js" type="text/javascript"></script>
<script src="//www.utexas.edu/cola/_internal/js/modernizr.js" type="text/javascript"></script>

{{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
<script src="//www.utexas.edu/cola/_internal/js/foundation.min.js" type="text/javascript"></script>
<script src="//www.utexas.edu/cola/_internal/js/app.js" type="text/javascript"></script>
{{ HTML::script('//cdn.datatables.net/1.10.6/js/jquery.dataTables.min.js') }}
{{ HTML::script('js/google_analytics.js') }}

</head>
<body>

@yield('page')