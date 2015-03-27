@extends('layout')

@section('title') Early Indo-European Online: Introduction to the Language Lessons @stop

@section('content')

@include('menu_lex')


	</div> <!-- close menu div -->
</div> <!-- close container for menu -->

<div id="contentmain"> <!-- open div for main content section -->

<!-- end Standard Header for new CoLA-style design -->


    
<h1>Indo-European Lexicon</h1>
<h2>Pokorny Master PIE Etyma</h2>

<p>The table below lists Proto-Indo-European (PIE) etyma adapted from Julius Pokorny's book,
<i lang='de'>Indogermanisches etymologisches W&ouml;rterbuch</i> (Bern: Francke, 1959, 1989). 
Entry head-words are listed, with their page numbers and cross-references to other entries
(following Pokorny) plus our own English glosses; for more information, the reader is referred 
to the book. Some misprints in Pokorny have been corrected, including repairs to diacritics and 
missing, incorrect, or extraneous homograph numbers.</p>

<blockquote>Note: this page is for systems/browsers
with <i>Unicode</i><sup>&reg;</sup> support and fonts spanning the <i>Unicode 3</i> character set relevant to Indo-European languages.


Versions of this page rendered in alternate character sets are available via links
(see <i>Unicode 2</i> and <i>ISO-8859-1</i>)
in the left margin.</blockquote>

<p>In our table, cross-references under "See also" may include IE links to our own lists 
of more modern Indo-European reflexes -- words derived from the ancient Proto-Indo-European 
etyma. IE reflex lists are <i>under active construction</i> (see our 
{{ HTML::link('lex', 'IE Lexicon', array('title' => 'Indo-European Lexicon (etyma and reflexes)' )) }}

home page): they are subject to change at any time, and might possibly exhibit errors not yet corrected. 
See our 
{{ HTML::link('lex_language', 'Language Index', array('title' => 'Indo-European Lexicon: IE Language Index' )) }}
page for links <i>from</i> reflexes (listed by IE language) <i>to</i> their PIE etyma.</p>


<table border='1' cellpadding='2' cellspacing='2' style='border-collapse: collapse' summary='Pokorny head-word entries with page numbers, cross-references, and English glosses'>
  <tr><th class='left' scope='col' width='11%'><nobr>Page(s)</nobr></th>
    <th class='left' scope='col' width='39%'>Pokorny entry</th>
    <th class='left' scope='col' width='14%'>See&nbsp;also</th>
    <th class='left' scope='col' width='36%'>English gloss</th></tr>
    
    
    
 <tr>
	 <td><span id='P0001' name='P0001'>1</span></td>
	 <td><span class='Unicode' lang='ine'>ā</span></td>
	 <td>{{ HTML::link('lex_reflex/1', 'IE', array('title' => 'RPokorny PIE etymon with Indo-European reflexes' )) }}</td>
	 <td>(an exclamation)</td>
 </tr>

 
 
 </table>
    
    
@stop