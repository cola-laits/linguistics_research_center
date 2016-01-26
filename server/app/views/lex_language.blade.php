@extends('layout')

@section('title') Early Indo-European Online: Introduction to the Language Lessons @stop

@section('content')

<div class="skinny">

<h1>Indo-European Lexicon</h1>
<h2>Language Indices</h2>

<p>Below we list Indo-European languages by family, from west to east. 
Families are divided into groups, by age and/or geographic area (again, 
generally from west to east). Each language is listed via a standard 
abbreviation followed by its full-form name; "<i>a.k.a.</i>" comments 
may indicate synonymous language/dialect names.</p>

<p>If we have prepared an alphabetic index to a particular language's 
reflexes -- words &amp; affixes therein, derived from ancient Proto-Indo-European 
etyma -- a link is provided to a page listing those reflexes. <i>This work is <b>in progress</b>: 
reflex indices are being added on a language-by-language basis, as 
time goes on, for languages with 10 or more reflexes</i>.</p>

<p>Because our IE Lexicon currently emphasizes <b>Germanic</b> 
languages, especially English, we have split the Germanic family 
into four convenient but partly imaginary sub-families:</p>
<ol>
<li><b>English</b> (technically part of Anglo-Frisian Low German);</li>
<li><b>West Germanic</b> (minus all English dialects, per above);</li>
<li><b>North Germanic</b> (the Norse/Scandinavian languages); and</li>
<li><b>East Germanic</b> (of which only Gothic is well-attested).</li>
</ol>

<p>In addition, our <b>Paleo-Balkan</b> "family" lists languages in 
four groups, of which <i>only</i> Albanian is well-attested and that 
only recently; the languages in the other groups <i>do not necessarily 
form a family</i> with Albanian (or each other), but are listed with 
it mainly for geographic reasons and because there is no other place 
to put them. No hard conclusions should be drawn from our Paleo-Balkan 
"family" structure: it is, at best, speculative!</p>

<p>While the <b>Nuristani</b> languages are now considered a third 
branch of the <b>Indo-Iranian</b> family, the status of the <b>Dardic</b> 
languages is less clear. Traditionally, Dardic was assigned to the Indic 
branch; but this was less than certain and, indeed, the languages in our 
Dardic "family" do not necessarily form a single genetic tree. We have 
tentatively listed Dardic alongside Indic, next to the Nuristani languages 
by which Dardic tongues are known to be influenced. Again, no hard 
conclusions should be drawn from this structural detail.</p>

<ul class="lang_list">
@foreach($language_families as $language_family)
    <li><strong>{{$language_family->name}}</strong></li>
    
    <li>
	    <ul class="lang_list">
		@foreach($language_family->language_sub_families as $language_sub_family)
		    <li><div class="lang_sub_family">{{$language_sub_family->name}}</div></li>
		    
		    <li>
			    <ul class="lang_list">
				@foreach($language_sub_family->languages as $language)
				    <li>
				    	<div class="lang_entry_1">{{$language->abbr}}.</div> 
					    @if ($language->reflex_count->first()['count'] > 10)
					    	<div class="lang_entry_2">
					    		{{ HTML::link('lex_lang_reflexes/' . $language->id, $language->name, array('title' => $language->name . ' reflex index')) }}
					    	</div>
					    @else
					    	<div class="lang_entry_2">{{$language->name}}</div>
					    @endif 
					    <div class="lang_entry_3">{{$language->aka}}</div>
				    </li>
				@endforeach
				</ul>
		    </li>
		    
		@endforeach
		</ul>
	</li>
    
    <li><hr/></li>
@endforeach
</ul>
</div>
@stop


@section('menu')
	@include('menu_menu')
	@include('menu_lex')
@stop