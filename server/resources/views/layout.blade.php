@extends('layout_header')
@section('page')

    @php
    $show_donation = Session::has('donation_page_ctr_limit') && Session::get('donation_page_ctr') <= Session::get('donation_page_ctr_limit')
    @endphp
    @if ($show_donation || Request::get('donate')==='true')
    <!-- donation box modal -->
    <style>
        /* The Modal (background) */
        .donation-modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .donation-modal-content {
            position: relative;
            background-color: #fefefe;
            margin: auto;
            padding: 0;
            border: 1px solid #888;
            width: 80%;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
            -webkit-animation-name: animatetop;
            -webkit-animation-duration: 0.4s;
            animation-name: animatetop;
            animation-duration: 0.4s
        }

        /* Add Animation */
        @-webkit-keyframes animatetop {
            from {top:-300px; opacity:0}
            to {top:0; opacity:1}
        }

        @keyframes animatetop {
            from {top:-300px; opacity:0}
            to {top:0; opacity:1}
        }

        /* The Close Button */
        .donation-modal-close {
            color: white;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .donation-modal-close:hover,
        .donation-modal-close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .donation-modal-header {
            padding: 2px 16px;
            background-color: #5cb85c;
            color: white;
        }

        .donation-modal-body {padding: 2px 16px;}

        .donation-modal-footer {
            padding: 2px 16px;
            background-color: #5cb85c;
            color: white;
        }
    </style>

    <div id="donationModal" class="donation-modal">

        <!-- Modal content -->
        <div class="donation-modal-content">
            <div class="donation-modal-header">
                <span class="donation-modal-close">&times;</span>
                <h2 style="color:white;">Donate to the Linguistics Research Center</h2>
            </div>
            <div class="donation-modal-body">
                <p></p>

                {!! Config::get('settings.donation_popup_text') !!}
            </div>
        </div>
    </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            var modal = document.getElementById("donationModal");
            var span = document.getElementsByClassName("donation-modal-close")[0];

            span.onclick = function() {
                modal.style.display = "none";
            }
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            modal.style.display = "block";
        });
    </script>
    <!-- end donation box modal -->
    @endif

<!--
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
All the content is wrapped in "off-canvas-wrap" and inner-wrap"
the page content is in <section class="main-section">
- this makes Phone Nav Possible
*************************************************************** -->

<div class="off-canvas-wrap" data-offcanvas="">
    <div class="inner-wrap">

        <div id="texas-bar">
            <div class="row">

                <!-- Phone "Tab" Bar Navigation-->

                <nav class="tab-bar show-for-small-only">
                  <section class="left tab-bar-section">
                    <a href="http://www.utexas.edu/">
                    <img alt="The University of Texas at Austin" class="texas" onerror="this.onerror=null;this.src='https://liberalarts.utexas.edu/_internal/images/2015_TEXAS_wordmark_white.png'" src="https://liberalarts.utexas.edu/_internal/images/2015_cola_texas3.svg"/></a>
                    <a href="http://liberalarts.utexas.edu/">
                    <img alt="College of Liberal Arts - The University of Texas at Austin" class="cla" onerror="this.onerror=null;this.src='https://liberalarts.utexas.edu/_internal/images/2015_TEXAS_wordmark_white.png'" src="https://liberalarts.utexas.edu/_internal/images/2015_cola_logo3.svg"/></a>
                  </section>

                </nav>

                <!--
                ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
                NOTE: Phone navigation uses TAB BAR - Tablet and higher uses TOP BAR
                ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ -->

                <!--
                *******************************************************
                PHONE NAVIGATION - TAB BAR  (1 of 2 orange bars up top)
                ******************************************************* -->

                <!-- Phone - off canvass navigation - the slide out navigation functionality -->

                <aside class="right-off-canvas-menu">
                    <ul class="off-canvas-list">

                        <!-- COLLEGE WIDE NAV - STATIC CONTENT -->
                        <li class="college closed">
                            <a class="off-canvas-submenu-call" href="#">
                                <img alt="The University of Texas at Austin" onerror="this.onerror=null;this.src='https://liberalarts.utexas.edu/_internal/images/images/2015_cola_logo_phone.png'" src="https://liberalarts.utexas.edu/_internal/images/2015_cola_logo_phone.svg"/>
                            </a>
                            <ul class="no-bullet off-canvas-submenu">
                                <li class="underline"><a href="http://liberalarts.utexas.edu/">College of Liberal Arts</a> </li>
                                <li><a href="http://www.utexas.edu/">University of Texas at Austin</a> </li>
                                <li><hr/></li>
                                <li class="underline"><a href="http://liberalarts.utexas.edu/offices/departments/">Departments</a> </li>
                                <li class="underline"> <a href="http://liberalarts.utexas.edu/research/graduate-studies/prospective">Graduate Resources</a> </li>
                                <li class="underline"> <a href="http://liberalarts.utexas.edu/student-affairs/Prospective/">Undergraduate Resources</a> </li>
                                <li class="underline"> <a href="http://liberalarts.utexas.edu/courses/">Courses</a> </li>
                                <li> <a href="http://online-education.la.utexas.edu/">Online Courses</a> </li>
                                <li><hr/></li>
                                <li class="underline"> <a href="http://liberalarts.utexas.edu/office-of-the-dean/college-leadership.php">Dean's Office</a> </li>
                                <li class="underline"> <a href="http://liberalarts.utexas.edu/alumni-and-giving/">Alumni &amp; Giving </a> </li>
                                <li> <a href="http://liberalarts.utexas.edu/public-affairs/resources/faculty-by-department.php">Faculty by Department</a> </li>
                                <li>
                                    <form action="https://liberalarts.utexas.edu/search/" id="search-small">
                                      <input name="cx" type="hidden" value="002688418440466237416:ilehtu0wbts"/>
                                      <input name="cof" type="hidden" value="FORID:10"/>
                                      <input name="ie" type="hidden" value="UTF-8"/>
                                      <label class="hidden-for-small-only" for="searchSmallOnlyInput">Search the College of Liberal Arts</label>
                                      <input id="searchSmallOnlyInput" name="q" placeholder="Search the College of Liberal Arts..." type="search"/>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        <!-- END COLLEGE WIDE NAV - STATIC CONTENT -->

                        <!-- UNIT NAV - DYNAMIC -->
                        <!-- Unit Name and Dynamic Nav -->

                        <!-- SYSTEM-REGION NAV-SM-UNIT -->
                        <li class="office"><h2><a href="http://www.utexas.edu/cola/centers/lrc/">Linguistics Research Center</a></h2></li>

                        <!-- END SYSTEM-REGION NAV-SM-UNIT -->
                    </ul> <!-- close ul class="off-canvas-list" -->
                    <!-- END UNIT NAV - DYNAMIC -->

                    <!-- Office Contacts and Maps Pages -->
                    <!-- SYSTEM-REGION NAV-SM-OFFICE -->

                    @yield('menu')


                    <ul class="side-nav">
                        <li><label>Office</label></li>
                        <li><a href="http://liberalarts.utexas.edu/lrc/staff.php">Staff List</a></li>
                        <li><a href="http://liberalarts.utexas.edu/lrc/contact-us.php">Contact Us</a></li>
                    </ul>
                    <!-- END SYSTEM-REGION NAV-SM-OFFICE -->

                    <!-- Address -->
                    <ul class="side-nav">
                        <li>
                            <label>Address</label>
                        </li>
                        <li class="phone-address">
                            <h6>Linguistics Research Center</h6>
                            <p>
                                University of Texas at Austin<br/>
                                PCL 5.556<br/>
                                Mailcode S5490<br/>
                                Austin, Texas 78712<br/>
                                512-471-4566
                            </p>
                        </li>
                    </ul>

                    <!-- Unit Social Media Links -->
                    <ul class="side-nav">
                        <li><label>Linguistics Research Center Social Media</label></li>
                        <li class="unit-social">
                            <a data-gtm-event="nav-phone-unit-facebook" href="https://www.facebook.com/UTLRC">
                                <i class="fa fa-facebook-square fa-2x">&#160;</i><span class="hide">Facebook</span>
                            </a>
                            <a data-gtm-event="nav-phone-unit-twitter" href="https://twitter.com/utlrc">
                                <i class="fa fa-twitter-square fa-2x">&#160;</i><span class="hide">Twitter</span>
                            </a>
                        </li>
                    </ul>
            <!-- Webmaster -->
            <ul class="side-nav">

                <li><label>E-mail Us!</label></li>
            <li class="phone-address">

                <p>For comments and inquiries, or to report issues, please contact the Web Master at <a href="mailto:UTLRC@utexas.edu">UTLRC@utexas.edu</a></p>

            </li>

            </ul>

                </aside>

                <!--
                ***************************************
                END END END PHONE NAVIGATION - TAB BAR
                ***************************************

                ***********************************************************************
                TABLET & DESKTOP ORANGE BAR LINKS - TOP BAR (2 of 2 orange bars up top)
                *********************************************************************** -->

                <nav class="top-bar show-for-medium-up" data-topbar="" role="navigation">
                    <ul class="title-area">
                        <li class="name">
                            <a href="http://www.utexas.edu/">
                                <img alt="The University of Texas at Austin" class="texas" src="https://liberalarts.utexas.edu/_internal/images/2015_cola_texas3.svg"/>
                            </a>
                            <a href="http://liberalarts.utexas.edu">
                                <img alt="College of Liberal Arts - The University of Texas at Austin" class="cla" onerror="this.onerror=null;this.src='https://liberalarts.utexas.edu/_internal/images/2015_TEXAS_wordmark_white.png'" src="https://liberalarts.utexas.edu/_internal/images/2015_cola_logo3.svg"/>
                            </a>
                        </li>
                        <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
                        <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a>
                        </li>
                    </ul>

                    <section class="top-bar-section">
                        <!-- Right Orange Bar Section -->
                        <ul class="right">
                            <li>
                                <!-- replaced by page-level template -->
                                <a class="donate-button" href="https://utdirect.utexas.edu/apps/utgiving/online/nlogon/?menu1=LALG" title="GIVE">GIVE</a>
                            </li>
                        </ul>
                    </section>
                </nav>
                <!--
                ***********************************************
                END DESKTOP ORANGE BAR LINKS - TOP BAR
                *********************************************** -->
            </div> <!-- Close <div class="row"> -->
        </div> <!-- Close <div id="texas-bar"> -->

        <section class="main-section">
            <!--
            ***************************************************************
            UNIT Word Mark -  Accomodate SVG and GIF Fallback - Load Mechanism into Cascade CMS
            *************************************************************** -->
            <div id="identity-bar">
                <div class="row">
                    <div class="row" id="logo_row">

                        <!-- replaced by page-level template -->
                        <div class="small-6 columns hide-for-small-only">
                            <a href="http://liberalarts.utexas.edu/lrc/index.php">
                                <img alt="" class="lockup" src="https://liberalarts.utexas.edu/_internal/images/logo-formal/Centers/LinguisticsResearchCenter.png"/>
                            </a>
                        </div>

                        <div class="small-12 columns hide-for-medium-up">
                            <a href="http://liberalarts.utexas.edu/lrc/index.php">
                                <img alt="" class="lockup" src="https://liberalarts.utexas.edu/_internal/images/logo-formal/Centers/LinguisticsResearchCenter.png"/>
                            </a>
                        </div>

                        <div class="small-6 columns hide-for-small-only">
                            <div id="donate-button">
                                <a href="https://utdirect.utexas.edu/apps/utgiving/online/nlogon/?menu1=LALG"><h3>Keep dead languages alive</h3></a>
                                <p class="hide-for-medium-down"><a href="https://utdirect.utexas.edu/apps/utgiving/online/nlogon/?menu1=LALG">We need your help to preserve &amp; document ancient languages. Participate today.</a></p>
                            </div>
                        </div>

                        <!-- replaced by page-level template -->

                        <!-- Unit Identity - "informal logo" -->
                    </div>
                </div>
            </div>

            <div class="row content-secondary-page">
                <!--
                ***************************************
                BODY CONTENT - "content-secondary-page"
                *************************************** -->
                <div class="medium-9 medium-push-3 columns">
                    <div class="row">
                        <!-- Page Main Image -->

                        <!-- SYSTEM-REGION PAGE-MAIN-IMAGE-H1 -->
                        <!-- END SYSTEM-REGION PAGE-MAIN-IMAGE-H1-->
                        <!-- END Page Main Image -->

                        <!--
                        - - - - - - - - - -
                        - - - - - - - - - -
                        MAIN BODY CONTENT
                        - - - - - - - - - -
                        - - - - - - - - - - -->

                        <!-- SYSTEM-REGION PAGE-BODY  -->
                        <div class="medium-12 columns">
                            @yield('content')
                        </div>


                        <!-- END SYSTEM-REGION PAGE-BODY  -->

                    </div>  <!-- END div class="row"  -->
                </div>  <!-- END div class="medium-9 medium-push-3 columns" -->

                <!--
                ****************
                END BODY CONTENT
                **************** -->

                <!--
                ***********************************************************************************
                OFFICE NAVIGATION = [MEDIUM UP ONLY] + RELATED LINKS + OFFICE + ADDRESS + SOC MEDIA
                *********************************************************************************** -->

                <div class="hide-for-small-only medium-3 medium-pull-9 columns content-unit-page-navigation">
                    <!-- Office Navigation -->
                    <hr class="show-for-small-only"/>

                    <!-- Unit Medium Up Navigation -->
                    <!-- Unit Links -->
                    <!-- SYSTEM-REGION NAV-MD-UP-UNIT -->

                    @yield('menu')

                    <!-- Address -->
                    <ul class="side-nav">
                        <li>
                            <label>Address</label>
                        </li>
                        <li class="phone-address">
                            <h6>Linguistics Research Center</h6>
                            <p>
                                University of Texas at Austin<br/>
                                PCL 5.556<br/>
                                Mailcode S5490<br/>
                                Austin, Texas 78712<br/>
                                512-471-4566
                            </p>
                        </li>
                    </ul>

                    <!-- Unit Social Media Links -->
                    <ul class="side-nav">
                        <li>
                            <label>Linguistics Research Center Social Media</label>
                        </li>
                        <li class="unit-social">
                            <a data-gtm-event="nav-unit-facebook" href="https://www.facebook.com/UTLRC">
                                <i class="fa fa-facebook-square fa-2x">&#160;</i>
                                <span class="hide">Facebook</span>
                            </a>
                            <a data-gtm-event="nav-unit-twitter" href="https://twitter.com/utlrc">
                                <i class="fa fa-twitter-square fa-2x">&#160;</i>
                                <span class="hide">Twitter</span>
                            </a>
                        </li>
                    </ul>
            <!-- Webmaster -->
            <ul class="side-nav">

                <li><label>E-mail Us!</label></li>
            <li class="phone-address">

                <p>For comments and inquiries, or to report issues, please contact the Web Master at <a href="mailto:UTLRC@utexas.edu">UTLRC@utexas.edu</a></p>

            </li>

            </ul>
                </div>
                <!--
                ***********************************************************************************
                END OFFICE NAVIGATION = [MEDIUM UP ONLY] + RELATED LINKS + OFFICE + ADDRESS + SOC MEDIA
                *********************************************************************************** -->

            </div>
            <!-- End Body Content <div class="medium-9 medium-push-3 columns"> -->

            <!--
            *************************
            FOOTER
            ************************* -->

            <!-- SYSTEM-REGION FOOTER -->
            <!-- Logo Social Media Row --><div class="row footer">
<div class="small-12 medium-5 large-4 columns">
<ul class="small-block-grid-1 logo-footer">
<li><a data-gtm-event="nav-college-footer-cla" href="http://liberalarts.utexas.edu/index.php"><img alt="The University of Texas at Austin College of Liberal Arts" height="45" onerror="this.onerror=null;this.src='https://liberalarts.utexas.edu/_internal/images/2015_colafooter_logo.png'" src="https://liberalarts.utexas.edu/_internal/images/2015_colafooter_logo.svg" width="280"/></a></li>
</ul>
</div>
<!-- Social Media Small -->
<div class="show-for-small-only small-12 columns social-media">
<hr class="show-for-small-only"/>
<ul class="small-block-grid-1">
<li><a class="donate-button center" data-gtm-event="nav-college-footer-giving" href="https://utdirect.utexas.edu/apps/utgiving/online/nlogon/?menu1=LALG" title="Make a Gift">Make a Gift</a></li>
</ul>
</div>
<div class="show-for-small-only show small-12 columns social-media">
<ul class="small-block-grid-5">
<li><a data-gtm-event="nav-college-footer-twitter" href="https://twitter.com/LiberalArtsUT"><em class="fa fa-twitter-square fa-3x">&#160;</em> <span class="hidden-for-small-only">Twitter</span></a></li>
<li><a data-gtm-event="nav-college-footer-youtube" href="http://www.youtube.com/user/LiberalArtsUT"><em class="fa fa-youtube-square fa-3x">&#160;</em> <span class="hidden-for-small-only">YouTube</span></a></li>
<li><a data-gtm-event="nav-college-footer-flickr" href="http://www.flickr.com/photos/utliberalarts/"><em class="fa fa-flickr fa-3x">&#160;</em> <span class="hidden-for-small-only">Flickr</span></a></li>
<li><a data-gtm-event="nav-college-footer-linkedin" href="http://www.linkedin.com/groups?home=&amp;gid=2237034"><em class="fa fa-linkedin-square fa-3x">&#160;</em> <span class="hidden-for-small-only">LinkedIn</span></a></li>
<li><a data-gtm-event="nav-college-footer-facebook" href="https://www.facebook.com/utliberalarts"><em class="fa fa-facebook-official fa-3x">&#160;</em> <span class="hidden-for-small-only">facebook</span></a></li>
</ul>
<hr class="show-for-small-only"/>
</div>
<!-- END Social Media Small --><!-- Social Media Medium Up -->
<div class="show-for-medium-up medium-7 large-8 columns">
<div class="row right">
<div class="small-5 small-centered medium-12 large-12 columns social-media"><a class="donate-button center right" data-gtm-event="nav-college-footer-giving" href="https://utdirect.utexas.edu/apps/utgiving/online/nlogon/?menu1=LALG" title="Make a Gift">Make a Gift</a><br class="show-for-medium-only"/>
 <a data-gtm-event="nav-college-footer-twitter" href="https://twitter.com/LiberalArtsUT"><em class="fa fa-twitter-square fa-2x">&#160;</em> <span class="hidden-for-medium-up">Twitter</span></a> <a data-gtm-event="nav-college-footer-youtube" href="http://www.youtube.com/user/LiberalArtsUT"><em class="fa fa-youtube-square fa-2x">&#160;</em> <span class="hidden-for-medium-up">YouTube</span></a> <a data-gtm-event="nav-college-footer-flickr" href="http://www.flickr.com/photos/utliberalarts/"><em class="fa fa-flickr fa-2x">&#160;</em> <span class="hidden-for-medium-up">Flickr</span></a> <a data-gtm-event="nav-college-footer-linkedin" href="http://www.linkedin.com/groups?home=&amp;gid=2237034"><em class="fa fa-linkedin-square fa-2x">&#160;</em> <span class="hidden-for-medium-up">LinkedIn</span></a> <a data-gtm-event="nav-college-footer-facebook" href="https://www.facebook.com/utliberalarts"><em class="fa fa-facebook-official fa-2x">&#160;</em> <span class="hidden-for-medium-up">facebook</span></a></div>
</div>
</div>
<!-- END Social Media Medium Up --></div>
<!-- End Logo Social Media Row -->
<div class="row footer" data-equalizer="">
<div class="small-6 medium-3 large-3 columns border-right" data-equalizer-watch="">
<div class="row">
<div class="small-11 small-offset-1 columns end">
<ul class="no-bullet">
<li>
<h3>Students</h3>
</li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/student-affairs/Prospective/">Prospective</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/student-affairs/">Undergraduate</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/research/">Graduate</a></li>
</ul>
<ul class="no-bullet">
<li><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/maps/">Campus Map</a></li>
</ul>
<br class="show-for-large-only"/>
<p class="hide-for-medium-only address" itemscope="" itemtype="http://schema.org/CollegeOrUniversity"><span itemprop="name">The College of Liberal Arts<br/>
 The University of Texas at Austin<br/>
</span> <link href="http://liberalarts.utexas.edu/" itemprop="sameAs"/> <span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress"><span itemprop="streetAddress">116 Inner Campus Dr Stop G6000</span><br/>
 <span itemprop="addressLocality">Austin</span>, <span itemprop="addressRegion">TX</span> <span itemprop="postalCode">78712</span></span></p>
<p class="hide-for-medium-only address" itemscope="" itemtype="http://schema.org/CollegeOrUniversity">General Inquiries:<br class="show-for-small-only"/>
 <span itemprop="telephone"><a data-gtm-event="nav-college-footer-phone-general" href="tel:512-471-4141">512-471-4141</a></span><br/>
<br/>
 Student Inquiries:<br class="show-for-small-only"/>
 <a data-gtm-event="nav-college-footer-phone-student" href="tel:512-471-4271"><span itemprop="telephone">512-471-4271</span></a></p>
</div>
</div>
</div>
<div class="large-3 show-for-large-up columns" data-equalizer-watch="">
<div class="row">
<div class="large-11 large-offset-1 show-for-large-up columns end">
<ul class="no-bullet">
<li>
<h3><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/offices/departments/">Departments</a></h3>
</li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/aads/">African &amp; African Diaspora Studies</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/afrotc/">Air Force Science</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/ams/">American Studies</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/anthropology/">Anthropology</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/asianstudies/">Asian Studies</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/classics/">Classics</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/economics/">Economics</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/english/">English</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/frenchitalian/">French &amp; Italian</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/geography/">Geography &amp; the Environment</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/germanic/">Germanic Studies</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/government/">Government</a></li>
</ul>
</div>
</div>
</div>
<div class="show-for-medium-up medium-4 large-3 columns border-right" data-equalizer-watch="">
<div class="row">
<div class="medium-11 medium-offset-1 show-for-medium-only columns end">
<ul class="no-bullet show-for-medium-only">
<li>
<h3><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/offices/departments/">Departments</a></h3>
</li>
<li>
<h3><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/offices/centers/">Centers</a></h3>
</li>
<li>
<h3><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/offices/institutes/">Institutes</a></h3>
</li>
<li>
<h3><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/offices/programs/">Programs</a></h3>
</li>
<li>
<h3><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/offices/initiatives/">Initiatives</a></h3>
</li>
<li>
<h3><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/office-of-the-dean/college-leadership.php">Administration</a></h3>
</li>
</ul>
</div>
<div class="large-11 large-offset-1 show-for-large-up columns end">
<ul class="no-bullet">
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/history/">History</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/linguistics/">Linguistics</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/mals/">Mexican American Latina/o Studies</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/mes/">Middle Eastern Studies</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/arotc/">Military Science</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/navymarine/">Naval Science</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/philosophy/">Philosophy</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/psychology/">Psychology</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/rs/">Religious Studies</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/rhetoric/">Rhetoric &amp; Writing</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/slavic/">Slavic &amp; Eurasian Studies</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/sociology/">Sociology</a></li>
<li><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/spanish/">Spanish &amp; Portuguese</a></li>
</ul>
</div>
</div>
</div>
<div class="small-6 medium-5 large-3 columns" data-equalizer-watch="">
<div class="row">
<div class="small-11 small-offset-1 columns end">
<ul class="no-bullet">
<li class="show-for-small-only">
<h3><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/offices/departments/">Departments</a></h3>
</li>
<li class="show-for-small-only">
<h3><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/offices/centers/">Centers</a></h3>
</li>
<li class="show-for-small-only">
<h3><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/offices/institutes/">Institutes</a></h3>
</li>
<li class="show-for-small-only">
<h3><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/offices/programs/">Programs</a></h3>
</li>
<li class="show-for-small-only">
<h3><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/offices/initiatives/">Initiatives</a></h3>
</li>
<li class="hide-for-medium-only">
<h3><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/office-of-the-dean/college-leadership.php">Administration</a></h3>
</li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/office-of-the-dean/">Office of the Dean</a></li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/academic-affairs/">Academic Affairs</a></li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/research/">Research &amp; Graduate Studies</a></li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/student-affairs/">Student Affairs</a></li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/business-affairs/">Business Affairs</a></li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/human-resources/">Human Resources</a></li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/alumni-and-giving/">Alumni &amp; Giving</a></li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/public-affairs/">Public Affairs</a></li>
<li class="show-for-large-up"><a data-gtm-event="nav-college-footer" href="http://liberalarts.utexas.edu/laits/">LAITS: IT &amp; Facilities</a></li>
<li class="show-for-medium-only address">The University of Texas at Austin<br/>
 116 Inner Campus Dr Stop G6000<br/>
 Austin, TX 78712</li>
<li class="show-for-medium-only address">General Inquiries:<br class="show-for-small-only"/>
 <a data-gtm-event="nav-college-footer-phone-general" href="tel:512-471-4141">512-471-4141</a><br/>
<br/>
 Student Inquiries:<br class="show-for-small-only"/>
 <a data-gtm-event="nav-college-footer-phone-student" href="tel:512-471-4271">512-471-4271</a></li>
</ul>
<p class="address"><a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cio/policies/web-privacy">Web Privacy Policy</a><br/>
 <a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/cio/policies/web-accessibility">Web Accessibility Policy</a><br/>
 <a data-gtm-event="nav-college-footer" href="http://www.utexas.edu/policies/dmca.html">&#169; Copyright</a> <span id="year">{{date('Y')}}</span></p>
</div>
</div>
</div>
</div>
            <!-- END SYSTEM-REGION FOOTER -->

        </section>

        <a class="exit-off-canvas"></a>

    </div>  <!-- Close <div class="inner-wrap"> -->
</div> <!-- Close <div class="off-canvas-wrap" data-offcanvas=""> -->


</body>
</html>
@stop
