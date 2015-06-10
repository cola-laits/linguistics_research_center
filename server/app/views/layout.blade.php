@extends('layout_header')
@section('page')
<!--
*************************
Orange UT Bar with Search
************************* -->
<div id="portal">
<div class="row">
<nav class="top-bar" data-topbar="">
<ul class="title-area">
<li class="ut-word-mark"><a href="//www.utexas.edu/"><img alt="The University of Texas at Austin" onerror="/dean/cola/_internal/images/2015_TEXAS_wordmark_white.png" src="//www.utexas.edu/cola/_internal/images/2015_TEXAS_wordmark_white.svg"/></a></li>
<!-- Hamburger / Pancake icon on phone -->
<li class="toggle-topbar menu-icon"><a><span></span></a></li>
</ul>
<section class="top-bar-section"><!-- Right Nav Section -->
<div class="right show-for-medium-up">
<ul>
<!-- SEARCH .right show-for-medium-up div -->
<li>
<form action="//www.utexas.edu/cola/search" id="search-medium-up">
<input name="cx" type="hidden" value="002688418440466237416:ilehtu0wbts"/> 
<input name="cof" type="hidden" value="FORID:10"/> 
<input name="ie" type="hidden" value="UTF-8"/> 
<label class="hidden-for-medium-up" for="searchMediumUpInput">Search the College of Liberal Arts</label>
<input id="searchMediumUpInput" name="q" placeholder="Search the College of Liberal Arts..." type="search"/>
</form>
</li>
<li> | </li>
<!-- Alumni Giving Give -->
<li class="portal-links"><a href="https://utdirect.utexas.edu/apps/utgiving/online/nlogon/?menu1=LA**&amp;source=LWE">GIVE</a></li>
</ul>
</div>
<!-- 
*************************
PHONE NAVIGATION
************************* -->
<div class="row show-for-small-only small-menu">
<!-- Phone Links -->
<div class="small-10 small-centered columns end">

          
          <a href="http://www.utexas.edu/cola/offices/departments/index.php"><div class="small-12 panel-phone"><h4>Academic Units</h4></div></a>
          <a href="http://www.utexas.edu/cola/research/index.php"><div class="small-12 panel-phone"><h4>Graduate Resources</h4></div></a>
          <a href="index.php"><div class="small-12 panel-phone"><h4>Undergraduate Resources</h4></div></a>
          <a href="http://www.utexas.edu/cola/courses/index.php"><div class="small-12 panel-phone"><h4>Courses</h4></div></a>
          <a href="http://online-education.la.utexas.edu/"><div class="small-12 panel-phone"><h4>Online Courses</h4></div></a>
          <hr/>
          <a href="http://www.utexas.edu/cola/office-of-the-dean/college-leadership.php"><div class="small-12 panel-phone"><h4>Dean's Office</h4></div></a>
          <a href="http://www.utexas.edu/cola/alumni-and-giving/index.php"><div class="small-12 panel-phone"><h4>Alumni &amp; Giving </h4></div></a>
          <a href="http://www.utexas.edu/cola/public-affairs/resources/faculty-by-department.php"><div class="small-12 panel-phone"><h4>Faculty by Department</h4></div></a>
          <hr/>
          <a href="http://www.utexas.edu/cola/public-affairs/resources/index.php"><div class="small-12 panel-phone"><h4>Staff &amp; Faculty Resources</h4></div></a>
          <a href="http://www.utexas.edu/cola/laits/index.php"><div class="small-12 panel-phone"><h4>LAITS: IT &amp; Facilities</h4></div></a>
</div>
<!-- Phone Search -->
<div class="small-8 small-centered columns end">
<form action="//www.utexas.edu/cola/search/" id="search-small">
<input name="cx" type="hidden" value="002688418440466237416:ilehtu0wbts"/> 
<input name="cof" type="hidden" value="FORID:10"/> <input name="ie" type="hidden" value="UTF-8"/> 
<label class="hidden-for-small-only" for="searchSmallOnlyInput">Search the College of Liberal Arts</label>
<input id="searchSmallOnlyInput" name="q" placeholder="Search the College of Liberal Arts..." type="search"/>
</form>
</div>
</div>
</section>
</nav>
</div>
</div>
<!-- #portal div -->
<!--    
***************************************************************
COLA Word Mark - Revisit to make better - Maybe make an H1 ????
*************************************************************** -->
<div id="word-mark">
	<div class="row">
		<a href="http://www.utexas.edu/cola/index.php"><img alt="College of Liberal Arts" onerror="/dean/cola/_internal/images/2015_cola_logo.gif" src="//www.utexas.edu/cola/_internal/images/2015_cola_logo.svg"/></a>
	</div>
</div>

<div id="donate-button">
	<a href="https://utdirect.utexas.edu/apps/utgiving/online/nlogon/?menu1=LALG"><h3>Keep dead languages alive</h3></a>
	<p><a href="https://utdirect.utexas.edu/apps/utgiving/online/nlogon/?menu1=LALG">We need your help to preserve &amp; document ancient languages. Participate today.</a></p>
</div>

<!--    
***************************************
BODY CONTENT - "content-secondary-page"
*************************************** -->

<div class="row content-secondary-page">
<div class="medium-9 medium-push-3 columns">
<div class="row">


        @yield('content')

     
        
<!--    
*************************
FOOTER 
************************* -->
<!-- Logo Social Media Row -->
<div class="row footer">
<div class="small-12 medium-5 large-4 columns">
<ul class="small-block-grid-1 logo-footer">
<li><a data-gtm-event="nav-college-footer-cla" href="http://www.utexas.edu/cola/index.php"><img alt="The University of Texas at Austin College of Liberal Arts" height="45" onerror="/dean/cola/_internal/images/2015_colafooter_logo.png" src="//www.utexas.edu/cola/_internal/images/2015_colafooter_logo.svg" width="280"/></a></li>
</ul>
</div>
<!-- Social Media Small -->
<div class="show-for-small-only small-12 columns social-media"><hr class="show-for-small-only"/>
<ul class="small-block-grid-1">
<li><a class="donate-button center" data-gtm-event="nav-college-footer-giving" href="https://utdirect.utexas.edu/apps/utgiving/online/nlogon/?menu1=LA" title="Make a Gift">Make a Gift</a></li>
</ul>
</div>
<div class="show-for-small-only show small-12 small-centered columns social-media">
<ul class="small-block-grid-5">
<li><a data-gtm-event="nav-college-footer-twitter" href="https://twitter.com/LiberalArtsUT"><i class="fa fa-twitter-square fa-3x">&#160;</i><span class="hidden-for-small-only"> Twitter </span></a></li>
<li><a data-gtm-event="nav-college-footer-youtube" href="http://www.youtube.com/user/LiberalArtsUT"><i class="fa fa-youtube-square fa-3x">&#160;</i><span class="hidden-for-small-only"> YouTube </span></a></li>
<li><a data-gtm-event="nav-college-footer-flickr" href="http://www.flickr.com/photos/utliberalarts/"><i class="fa fa-flickr fa-3x">&#160;</i><span class="hidden-for-small-only"> Flickr </span></a></li>
<li><a data-gtm-event="nav-college-footer-linkedin" href="http://www.linkedin.com/groups?home=&amp;gid=2237034"><i class="fa fa-linkedin-square fa-3x">&#160;</i><span class="hidden-for-small-only"> LinkedIn </span></a></li>
<li><a data-gtm-event="nav-college-footer-facebook" href="https://www.facebook.com/utliberalarts"><i class="fa fa-facebook-official fa-3x">&#160;</i><span class="hidden-for-small-only"> facebook </span></a></li>
</ul>
<hr class="show-for-small-only"/></div>
<!-- END Social Media Small --> <!-- Social Media Medium Up -->
<div class="show-for-medium-up medium-7 large-8 columns">
<div class="row right">
<div class="small-5 small-centered medium-12 large-12 columns social-media"><a class="donate-button center right" data-gtm-event="nav-college-footer-giving" href="https://utdirect.utexas.edu/apps/utgiving/online/nlogon/?menu1=LA" title="Make a Gift">Make a Gift</a> <br class="show-for-medium-only"/> <a data-gtm-event="nav-college-footer-twitter" href="https://twitter.com/LiberalArtsUT"><i class="fa fa-twitter-square fa-2x">&#160;</i><span class="hidden-for-medium-up"> Twitter </span></a> <a data-gtm-event="nav-college-footer-youtube" href="http://www.youtube.com/user/LiberalArtsUT"><i class="fa fa-youtube-square fa-2x">&#160;</i><span class="hidden-for-medium-up"> YouTube </span></a> <a data-gtm-event="nav-college-footer-flickr" href="http://www.flickr.com/photos/utliberalarts/"><i class="fa fa-flickr fa-2x">&#160;</i><span class="hidden-for-medium-up"> Flickr </span></a> <a data-gtm-event="nav-college-footer-linkedin" href="http://www.linkedin.com/groups?home=&amp;gid=2237034"><i class="fa fa-linkedin-square fa-2x">&#160;</i><span class="hidden-for-medium-up"> LinkedIn </span></a> <a data-gtm-event="nav-college-footer-facebook" href="https://www.facebook.com/utliberalarts"><i class="fa fa-facebook-official fa-2x">&#160;</i><span class="hidden-for-medium-up"> facebook </span></a></div>
</div>
</div>
<!-- END Social Media Medium Up --></div>
<!-- End Logo Social Media Row -->
<div class="row footer" data-equalizer="">
<div class="small-6 medium-3 large-3 columns border-right " data-equalizer-watch="">
<div class="row">
<div class="small-11 small-offset-1 columns end">
<ul class="no-bullet">
<li>
<h3>Students</h3>
</li>
<li><a data-gtm-event="nav-college-footer" href="Prospective/index.php">Prospective</a></li>
<li><a data-gtm-event="nav-college-footer" href="index.php">Undergraduate</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/research/index.php">Graduate</a></li>
</ul>
<ul class="no-bullet">
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/maps/">Campus Map</a></li>
</ul>
<br class="show-for-large-only"/>
<p class="hide-for-medium-only address" itemscope="" itemtype="http://schema.org/CollegeOrUniversity"><span itemprop="name">The College of Liberal Arts<br/> The University of Texas at Austin<br/></span> <link href="http://www.utexas.edu/cola/index.php" itemprop="sameAs"/> <span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress"> <span itemprop="streetAddress">116 Inner Campus Dr Stop G6000</span><br/> <span itemprop="addressLocality">Austin</span>, <span itemprop="addressRegion">TX</span> <span itemprop="postalCode">78712</span> </span></p>
<p class="hide-for-medium-only address" itemscope="" itemtype="http://schema.org/CollegeOrUniversity">General Inquiries: <br class="show-for-small-only"/><span description="General Inquiries" itemprop="telephone"><a data-gtm-event="nav-college-footer-phone-general" href="tel:512-471-4141">512-471-4141</a></span><br/><br/> Student Inquiries: <br class="show-for-small-only"/><a data-gtm-event="nav-college-footer-phone-student" href="tel:512-471-4271"><span description="Student Inquiries" itemprop="telephone">512-471-4271</span></a></p>
</div>
</div>
</div>
<div class="large-3 show-for-large-up columns" data-equalizer-watch="">
<div class="row">
<div class="large-11 large-offset-1 show-for-large-up columns end">
<ul class="no-bullet">
<li>
<h3><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/offices/departments/index.php">Departments</a></h3>
</li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/aads/">African &amp; African Diaspora Studies</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/afrotc/">Air Force Science</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/ams/">American Studies</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/anthropology/">Anthropology</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/asianstudies/">Asian Studies</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/classics/">Classics</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/economics/">Economics</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/english/">English</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/frenchitalian/">French &amp; Italian</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/geography/">Geography &amp; the Environment</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/germanic/">Germanic Studies</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/government/">Government</a></li>
</ul>
</div>
</div>
</div>
<div class="show-for-medium-up medium-4 large-3 columns border-right" data-equalizer-watch="">
<div class="row">
<div class="medium-11 medium-offset-1 show-for-medium-only columns end">
<ul class="no-bullet show-for-medium-only ">
<li>
<h3><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/offices/departments/index.php">Departments</a></h3>
</li>
<li>
<h3><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/offices/centers/index.php">Centers</a></h3>
</li>
<li>
<h3><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/offices/institutes/index.php">Institutes</a></h3>
</li>
<li>
<h3><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/offices/programs/index.php">Programs</a></h3>
</li>
<li>
<h3><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/offices/initiatives/index.php">Initiatives</a></h3>
</li>
<li>
<h3><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/office-of-the-dean/college-leadership.php">Administration</a></h3>
</li>
</ul>
</div>
<div class="large-11 large-offset-1 show-for-large-up columns end">
<ul class="no-bullet">
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/history/">History</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/linguistics/">Linguistics</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/mals/">Mexican American Latina/o Studies</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/mes/">Middle Eastern Studies</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/arotc/">Military Science</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/navymarine/">Naval Science</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/philosophy/">Philosophy</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/psychology/">Psychology</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/rs/">Religious Studies</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/rhetoric/">Rhetoric &amp; Writing</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/slavic/">Slavic &amp; Eurasian Studies</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/sociology/">Sociology</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/depts/spanish/">Spanish &amp; Portuguese</a></li>
</ul>
</div>
</div>
</div>
<div class="small-6 medium-5 large-3 columns" data-equalizer-watch="">
<div class="row">
<div class="small-11 small-offset-1 columns end">
<ul class="no-bullet">
<li class="show-for-small-only">
<h3><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/offices/departments/index.php">Departments</a></h3>
</li>
<li class="show-for-small-only">
<h3><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/offices/centers/index.php">Centers</a></h3>
</li>
<li class="show-for-small-only">
<h3><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/offices/institutes/index.php">Institutes</a></h3>
</li>
<li class="show-for-small-only">
<h3><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/offices/programs/index.php">Programs</a></h3>
</li>
<li class="show-for-small-only">
<h3><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/offices/initiatives/index.php">Initiatives</a></h3>
</li>
<li class="hide-for-medium-only">
<h3><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/office-of-the-dean/college-leadership.php">Administration</a></h3>
</li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/office-of-the-dean/index.php">Office of the Dean</a></li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/academic-affairs/index.php">Academic Affairs</a></li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/research/index.php">Research &amp; Graduate Studies</a></li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/student-affairs/index.php">Student Affairs</a></li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/business-affairs/index.php">Business Affairs</a></li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/human-resources/index.php">Human Resources</a></li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/alumni-and-giving/index.php">Alumni &amp; Giving</a></li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/public-affairs/index.php">Public Affairs</a></li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cola/laits/index.php">LAITS: IT &amp; Facilities</a></li>
<li class="show-for-medium-only address">The University of Texas at Austin<br/> 116 Inner Campus Dr Stop G6000<br/> Austin, TX 78712</li>
<li class="show-for-medium-only address">General Inquiries: <br class="show-for-small-only"/> <a data-gtm-event="nav-college-footer-phone-general" href="tel:512-471-4141">512-471-4141</a><br/><br/> Student Inquiries: <br class="show-for-small-only"/><a data-gtm-event="nav-college-footer-phone-student" href="tel:512-471-4271">512-471-4271</a></li>
</ul>
<p class="address"><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/policies/privacy/">Web Privacy Policy</a><br/> <a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/web/guidelines/accessibility.html">Web Accessibility Policy</a><br/> <a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/policies/dmca.html">&#169; Copyright</a> <span id="year">TEXT</span></p>
</div>
</div>
</div>
</div>


</body>
</html>
@stop