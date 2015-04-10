<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>	<!-- Standard Header for new CoLA-style design with Balkan tree leaf -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name='Generator' content='doStdHtmlX.pl' />
<title>@section('title') The Linguistics Research Center @show</title>
{{ HTML::style('css/lrcstyle.css') }}
{{ HTML::style('https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css') }}
        
{{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}

<!-- Dublin Core Metadata (DC) -->
<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />

<!-- language of the content -->
<meta name="DC.Language" scheme="ISO639-1" content="en" />

<!-- name of resource assigned by publisher -->
<meta name="DC.Title" lang="en-US" content="Early Indo-European Online" />

<!-- subject of resource: keywords and/or descriptive phrases -->
<meta name="DC.Subject" lang="en-US" content="Early Indo-European Online, Early Indo-European Languages, Classical Armenian, Baltic (Lithuanian and Latvian), Old English (Anglo-Saxon), Old French, Gothic, Greek (Classical Greek and New Testament Greek), Hittite, Old Irish, Latin, Old Church Slavonic (Old Church Slavic), Old Iranian (Avestan and Old Persian), Old Norse (Old Icelandic), Old Russian (Old East Slavic), Sanskrit (Rigvedic)" />

<!-- Library of Congress Classification -->
<meta name="DC.Subject" scheme="LCC" content="PA" />

<!-- Library of Congress Subject Headings -->
<meta name="DC.Subject" scheme="LCSH" content="Indo-European languages" />
<meta name="DC.Subject" scheme="LCSH" content="Philology" />

<!-- textual description (abstract) of page content -->
<meta name="DC.Description" lang="en-US" content="Early Indo-European Online. These online Language Introductions are designed to provide the ability to read early Indo-European texts, with or without the help of translations." />

<!-- person(s) or organization primarily responsible for creating the intellectual content -->
<meta name="DC.Creator" content="Jonathan Slocum" />

<!-- significant contributor(s) other than the creator -->
<meta name="DC.Contributor" content="Winfred P. Lehmann" />

<!-- spatial and/or temporal characteristics of the resource -->
<meta name="DC.Coverage" content="ca. 17th century B.C. - 20th century A.D." />

<!-- agency responsible for making resource available -->
<meta name="DC.Publisher" lang="en-US" content="Linguistics Research Center, The University of Texas at Austin" />
<meta name="DC.Publisher.Address" content="UTLRC@uts.cc.utexas.edu" />

<!-- publisher's copyright statement -->
<meta name="DC.Rights" lang="en-US" content="Copyright (c) 2002-2014, The University of Texas at Austin" />

<!-- creation/modification date of this page in YYYY-MM-DD format -->
<meta name="DC.Date" scheme="ISO8601" content="2014-05-13" />

<!-- type of content (e.g., text, image, sound) to suggest the hardware/software required to serve this page -->
<meta name="DC.Type" content="Text" />

<!-- format, i.e., physical or digital manifestation of the resource -->
<meta name="DC.Format" content="text/html" />

<!-- URL for page -->
<meta name="DC.Identifier" content="http://www.utexas.edu/cola/centers/lrc/eieol/index.html" />

<!-- For search engines that do not support Dublin Core metadata, include the old-style META tags -->
<meta name="description" content="Early Indo-European Online. These online Language Introductions are designed to provide the ability to read early Indo-European texts, with or without the help of translations." />
<meta name="keywords" content="Early Indo-European Online, Early Indo-European Languages, Classical Armenian, Baltic (Lithuanian and Latvian), Old English (Anglo-Saxon), Old French, Gothic, Greek (Classical Greek and New Testament Greek), Hittite, Old Irish, Latin, Old Church Slavonic (Old Church Slavic), Old Iranian (Avestan and Old Persian), Old Norse (Old Icelandic), Old Russian (Old East Slavic), Sanskrit (Rigvedic)" />

<meta name="author" content="Jonathan Slocum and Winfred P. Lehmann" />
</head>

<body>


<!-- Google Analytics - December 2 2013 -->
{{ HTML::script('js/googanalyt.js') }}
<!-- End Google Analytics -->
<!-- Google Tag Manager - December 2 2013 -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WB33VF" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
{{ HTML::script('js/googtagman.js') }}
<!-- End Google Tag Manager -->


<a id="TopOfPage" name="TopOfPage"></a>
<div id="frame"><!-- open frame for webpage -->
	<div id="contentheader"> <!-- open div for header area -->
		<div id="wordmarks"> <!-- open wordmarks for UT and COLA --><a title="main content" href="#contentmain" style="text-decoration:none">&nbsp;</a>
			<img src="http://www.utexas.edu/cola/centers/lrc/images/wordmarks_utla.gif" alt="The University of Texas at Austin; College of Liberal Arts" />
		</div>
		<div id="banner"> <!-- open div for banner graphic -->

			{{ HTML::image('images/LRCbannerB.jpg', $alt="Linguistics Research Center", $attributes = array('border'=>0, 'width'=>900, 'height'=>120)) }}
		</div> <!-- close banner graphic -->
	</div> <!-- close contentheader -->

<div id="contact_info"> <!-- open div for contact info if desired -->
   Hans C. Boas, Director :: PCL 5.556, 1 University Station S5490 :: Austin, TX 78712 :: 512-471-4566 <br />
</div>

<div id="header_navbar">LRC Links:
	{{ HTML::link('index', 'Home', array('title' => 'Linguistics Research Center home page')) }}  |
	{{ HTML::link($static_site . 'about/', 'About', array('title' => 'About the Linguistics Research Center')) }}  |
	{{ HTML::link($static_site . 'books/', 'Books Online', array('title' => 'Indo-European Languages and Historical Linguistics (books online)')) }}  |
	{{ HTML::link('eieol', 'EIEOL', array('title' => 'Early Indo-European Online (language lessons)')) }}  |
	{{ HTML::link($static_site . 'iedocctr/', 'IE Doc. Center', array('title' => 'Indo-European Documentation Center')) }}  |
	{{ HTML::link('lex', 'IE Lexicon', array('title' => 'Indo-European Lexicon (etyma and reflexes)')) }}  |
	{{ HTML::link($static_site . 'general/IE.html', 'IE Maps', array('title' => 'Indo-European Languages: Evolution and Locale Maps')) }}  |
	{{ HTML::link($static_site . 'ietexts', 'IE Texts', array('title' => 'Early Indo-European Texts (most with English translations)')) }}  |
	{{ HTML::link($static_site . 'indices', 'Pub. Indices', array('title' => 'Publication Indices (mostly to articles about Indo-European)')) }}  |
	{{ HTML::link($static_site . 'sitemap.html', 'SiteMap', array('title' => 'Linguistics Research Center sitemap')) }}  |
</div>

<br />

<div id="contentleft"> <!-- open left-hand menu container -->
	<div class="menu"> <!-- open menu list div -->
	
	
	





        @yield('content')

        
        
        
        
        
<!-- begin Standard Footer for new CoLA-style design with provision for NavBar -->

	<p>
	  <!-- without this little <br /> NS6 and IE5PC do not stretch the frame div down to encompass the content DIVs -->
	</p>
	<!-- TemplateEndEditable --></div>	<!-- close main content div -->
<hr />
<div id="footer" align="right">	<!-- open footer div -->
<p>Last Updated: Tuesday, 13 May 2014, 11:10<br />
<a href="http://www.utexas.edu/cola/centers/lrc/" title="Linguistics Research Center">Linguistics Research Center </a> in<br />
<a href="http://www.utexas.edu/cola/" title="The College of Liberal Arts">The College of Liberal Arts</a> at
<a href="http://www.utexas.edu/" title="The University of Texas at Austin">UT Austin</a><br />
Information on:&nbsp;<a href="http://www.utexas.edu/policies/dmca.html" title="UT Campus IT Policies">Copyright</a> | 
<a href="http://www.utexas.edu/policies/privacy/" title="UT Web Privacy Policy">Privacy</a> | 
<a href="http://www.utexas.edu/web/guidelines/accessibility.html" title="UT Web Accessibility Policy">Accessibility</a><br />

Email&nbsp;<a href="mailto:UTLRC@uts.cc.utexas.edu" title="Send email to LRC">comments</a></p>
</div>	<!-- close footer div -->
</div> <!-- close frame container div -->
</body>
</html>