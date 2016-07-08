@extends('layout')

@section('title') Early Indo-European Online: Introduction to the Language Lessons @stop

@section('content')
    
<h1>Indo-European Lexicon</h1>
<h2>PIE Etyma and IE Reflexes</h2>
<h3 class='AUTH'>Jonathan Slocum</h3>

<p>Our project goal is to produce a large, heavily indexed collection of Indo-European (IE) 
"reflex" words having their inferred etymological origins in the reconstructed ancestral 
language Proto-Indo-European (PIE). Re: "large," we anticipate many tens of thousands of reflex 
entries (the present size already exceeding 60,000). By "heavily indexed," we mean every unique 
reflex spelling in the collection can be indexed alphabetically within its language and family
(there are already nearly 70,000 indexed reflex spellings in 100 IE languages/dialects), so 
that one can click on browser links to see all information associated with each reflex word 
and its relationship to other words. There will be no database search engine, nor any need for 
one.</p>

<p>What we originally had, as a lexicon, comprised nothing more than a collection of [most of] the 
main entries -- "etyma" -- in Julius Pokorny's massive <i lang='de'>Indogermanisches etymologisches 
W&ouml;rterbuch</i> (IEW), along with our own glosses of their meanings and chains of cross-references 
derived from IEW. Our project's next step required, among other things, human editing of massive 
content assembled via software from electronic sources; additional content is now being acquired 
from selected print &amp; online sources. For those who are interested, an 
<a title="Building an Indo-European Lexicon" href="https://liberalarts.utexas.edu/lrctr/resources/indo-european-lexicon.php">online paper</a> 
outlines the nature of our early work in this project.</p>

<p>Work on our IE Lexicon has become our primary focus. For most of our 
{{ HTML::link('eieol', 'EIEOL', array('title' => 'Early Indo-European Online (language lessons)' )) }}

lesson languages, we have attempted (or will attempt) to link relevant entries in their 
Base-Form Dictionaries to etyma in our <b>Pokorny Master Collection</b>. In addition, a large 
and growing fraction of the PIE etyma listed in our Pokorny collection are being linked to 
<b>IE Reflex Pages</b> that list words derived from those etyma: at present, nearly 200 ancient 
and modern Indo-European languages/dialects are represented by reflexes, the vast majority 
of which may be located alphabetically via our <b>Language Index</b> pages. Our lower-level 
<b>Semantic Field Index</b> pages may also be linked to <b>IE Reflex Pages</b>.</p>

<h4>Pokorny Master Collection</h4>

<p>As our current set of 
{{ HTML::link('lex/master', 'PIE etyma', array('title' => 'Pokorny PIE Data' )) }},
we have selected 2,222 main entries from Pokorny's IEW; 
these are listed in a single large table in their IEW "alphabetic" order. 
Each entry that corresponds to a page listing IE reflexes thereof is linked to that page. At 
present, over 2/3 of our PIE entries link to <b>IE Reflex Pages</b>; this fraction will rise 
as our project proceeds.</p>

<h4>IE Reflex Pages</h4>

<p>Each IE reflex page, shows a single PIE etymon with reflexes in IE languages/dialects. Each 
reflex is annotated with: part-of-speech and/or other grammatical feature(s); a short gloss 
which, especially for modern English reflexes, may be confined to the oldest sense; and one 
or more source citation(s). Again there are three versions of each etymon-with-reflexes page; 
each is linked, in chain-reference fashion, to nearby etyma (the previous and/or next extant 
reflex page in IEW order) --</p>

<h4>Language Index</h4>

<p>Our IE {{ HTML::link('lex/languages', 'Language Index', array('title' => 'Indo-European Lexicon: IE Language Index' )) }}
page lists many (though not all) individual Indo-European languages by family, from west to east; families 
are divided into groups, by age and/or geographic area (again, generally from west to east). For each IE 
"daughter language" that is represented by a sufficient number of reflex words derived from PIE etyma, a 
<b>Reflex Index</b> page will exist: each reflex index will list, in an alphabetic order suitable for the 
language family, all words in the language/dialect that appear on <b>IE Reflex Pages</b>. A word with 
multiple morphemes may have multiple links to IE reflex pages (e.g., the English noun <i>werewolf</i> 
'man-wolf' derives from two PIE etyma). Also, since different words spelled the same way may derive from 
different PIE etyma, again there may be multiple links (e.g., the English verbs <i>lie</i> 'to recline' 
and <i>lie</i> 'to prevaricate' link to their different PIE etyma). And, obviously, many words in a given 
language (e.g. English <i>brown</i>, <i>bruin</i>, <i>bear</i> 'animal') may derive from a single PIE etymon.</p>


<h4>Semantic Field Index</h4>

<p>Another feature of our collection is a 
{{ HTML::link('lex/semantic', 'Semantic Index', array('title' => 'Indo-European Linguistics: Semantic Fields' )) }}
to the Proto-Indo-European etyma listed in Pokorny, using a scheme developed by Carl Darling Buck 
(cf. <i>A Dictionary of Selected Synonyms in the Principal Indo-European Languages</i>, 1949). This 
semantic indexing scheme has been used by others and, while not perfect, seems adequate for our needs. 
We are in the process of making substantial additions to our lexical collection, adding "reflex" words 
derived from PIE etyma as listed by Pokorny; these can be reached via links on our lower-level 
{{ HTML::link('lex/semantic', 'Semantic Index', array('title' => 'Indo-European Linguistics: Semantic Fields' )) }}
pages. At present such links are mildly limited in number; but check back from time to time for new 
resources, as this work is proceeding swiftly.</p>
@stop


@section('menu')
	@include('menu_menu')
	@include('menu_lex')
@stop