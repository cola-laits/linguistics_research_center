<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" class="no-js" lang="en">
<head>

<!--  This is a separate file so it can be called from layout.blade, 
but also used to make printable lessons in the public controller -->

<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0 maximum-scale=1" name="viewport"/>

<!-- FAVICONS -->
    <link href="https://liberalarts.utexas.edu/_internal/images/favicons/favicon.ico" rel="icon"/>
    <link href="https://liberalarts.utexas.edu/_internal/images/favicons/apple-touch-icon-180x180.png" rel="apple-touch-icon" sizes="180x180"/>
    <link href="https://liberalarts.utexas.edu/_internal/images/favicons/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152"/>
    <!--[if IE]><link rel="shortcut icon" href="https://liberalarts.utexas.edu/_internal/images/favicons/favicon.ico" /><![endif]-->
    <meta content="https://liberalarts.utexas.edu/_internal/images/faviacons/apple-touch-icon-144x144.png" name="msapplication-TileImage"/>
    <meta content="#ffffff" name="msapplication-TileColor"/>
    <meta content="UT Austin" name="apple-mobile-web-app-title"/>
    @section('meta') @show
    <link href="https://liberalarts.utexas.edu/_internal/images/favicons/apple-touch-icon-144x144.png" rel="apple-touch-icon" sizes="144x144"/>
    <link href="https://liberalarts.utexas.edu/_internal/images/favicons/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120"/>
    <link href="https://liberalarts.utexas.edu/_internal/images/favicons/apple-touch-icon.png" rel="apple-touch-icon"/>
    <link href="https://liberalarts.utexas.edu/_internal/images/favicons/apple-touch-icon-57x57.png" rel="apple-touch-icon" sizes="57x57"/>
    <link href="https://liberalarts.utexas.edu/_internal/images/favicons/apple-touch-icon-60x60.png" rel="apple-touch-icon" sizes="60x60"/>
    <link href="https://liberalarts.utexas.edu/_internal/images/favicons/apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76"/>
    <link href="https://liberalarts.utexas.edu/_internal/images/favicons/apple-touch-icon-72x72.png" rel="apple-touch-icon" sizes="72x72"/>
    <link href="https://liberalarts.utexas.edu/_internal/images/favicons/apple-touch-icon-114x114.png" rel="apple-touch-icon" sizes="114x114"/>
<!-- END FAVICONS -->

<!-- SYSTEM-PAGE-TITLE and META-DATA-->
<title>@section('title') The Linguistics Research Center @show</title>
<!-- END SYSTEM-PAGE-TITLE and META-DATA-->

<!-- CSS LOADING -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,700,700italic,400italic|Roboto+Condensed:400,300" rel="stylesheet" type="text/css"/>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://liberalarts.utexas.edu/_internal/css/app_units.css" rel="stylesheet" type="text/css"/>
    
	{{ HTML::style('css/lrcstyle.css') }}
	{{ HTML::style('//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css') }}
	{{ HTML::style('//cdn.datatables.net/1.10.6/css/jquery.dataTables.min.css') }}
	{{ HTML::style('css/demo_table_jui.css') }}
<!-- END CSS LOADING -->

<!--  "Must Have in the Header" Javascript -->
	<script src="//jwpsrv.com/library/1O4izvC8EeKppRIxOQulpA.js" type="text/javascript"></script> <!-- JW PLAYER -->
	<script src="https://liberalarts.utexas.edu/_internal/js/modernizr.js" type="text/javascript"></script>
	{{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
	{{ HTML::script('//cdn.datatables.net/1.10.6/js/jquery.dataTables.min.js') }}
	
	<!-- QUALTRICS survey popup - Feb 27 2017 -->
	<script language="JavaScript" src="https://liberalarts.utexas.edu/lrc/_files/scripts/survey.js" type="text/javascript">// <![CDATA[ // ]]></script>

	<noscript>Take <a href="https://liberalarts.utexas.edu/lrc/_files/scripts/survey" title="LRC Visitor Survey">our survey</a></noscript>
	<!-- End QUALTRICS survey popup -->
<!-- END "Must Have in the Header" Javascript -->

</head>
<body>

@yield('page')
