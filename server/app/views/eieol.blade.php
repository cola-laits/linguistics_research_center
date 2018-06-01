@extends('layout')

@section('title') Early Indo-European Online: Introduction to the Language Lessons @stop

@section('content')

    <h1>Early Indo-European Online</h1>
	<h2>Introduction to the Language Lessons</h2>
	<h3 class='AUTH'>Jonathan Slocum and Winfred P. Lehmann</h3>
<div class="skinny">


{{ $content }}


<h4>The Lesson Texts</h4>

    
    <ul>
    @foreach($serieses as $series)
        <li>{{ HTML::link('eieol/' . $series->slug, $series->title, array('title' => $series->expanded_title )) }} </li>
    @endforeach
  	</ul>
    
<h4>Related Language Courses at UT</h4>

<p>Most but not all language courses taught at The University of Texas concern
modern languages; sometimes courses are offered in ancient languages, though
more often at the graduate level. Interested students are referred to the relevant 
departmental websites for details; links to them below open in a new browser window, 
leaving this one intact.</p>

<ul>
<li>Courses in Latin and ancient Greek are taught in the 
<a title="Classics Department website" href="http://liberalarts.utexas.edu/depts/classics/" target='new'>Department of Classics</a>;</li>
<li>Slavic language courses are taught in the 
<a title="Slavic &amp; Eurasian Studies Department website" href="http://liberalarts.utexas.edu/depts/slavic/" target='new'>Department of Slavic &amp; Eurasian Studies</a>;</li>
<li>Iranian language courses are taught in the 
<a title="Middle Eastern Studies Department website" href="http://liberalarts.utexas.edu/depts/mes/" target='new'>Department of Middle Eastern Studies</a>;</li>
<li>Germanic language courses are taught in the 
<a title="Germanic Studies Department website" href="http://liberalarts.utexas.edu/depts/germanic/" target='new'>Department of Germanic Studies</a>,<br />
with the exception of English which is taught in the 
<a title="English Department website" href="http://liberalarts.utexas.edu/depts/english/" target='new'>Department of English</a>;</li>
<li>Indic language courses, including Sanskrit, are taught in the 
<a title="Asian Studies Department website" href="http://liberalarts.utexas.edu/depts/asianstudies/" target='new'>Department of Asian Studies</a>;</li>
<li>Romance (post-Latin) language courses are taught in the 
<a title="French &amp; Italian Department website" href="http://liberalarts.utexas.edu/depts/frenchitalian/" target='new'>Department of French &amp; Italian</a><br />and 
the <a title="Spanish &amp; Portuguese Department website" href="http://liberalarts.utexas.edu/depts/spanish/" target='new'>Department of Spanish &amp; Portuguese</a>.</li>
</ul>
    
    
    
<p>Online language courses for college credit are offered through the 
<a title="Online College Credit Courses" href="http://www.utexas.edu/ce/uex/online/" target='new'>University Extension</a>
(link opens in new window).</p>

<h4>Indo-European Language Resources Elsewhere</h4>

<p>Our <a title="Offsite Web Links" href="http://liberalarts.utexas.edu/lrc/links.php">Web Links</a> page includes pointers to 
<a title="Links to other websites with Indo-European language resources" href="http://liberalarts.utexas.edu/lrc/links.php#PIE">Indo-European language resources elsewhere</a>.</p>
</div>
@stop


@section('menu')
	@include('menu_menu')
	@include('menu_series', array('data'=>'data'))
	@include('menu_book_links')
@stop